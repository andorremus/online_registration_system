<?php
require_once('Seminar.php');
require_once('Speaker.php');


class databaseConnection
{
    private $link;

    public function __construct($host,$user,$pass,$database)
    {
        $this->link = new mysqli($host,$user,$pass,$database);

        if($this->link->connect_errno)
        {
            die('Unable to connect to database: ' . $this->link->connect_error );
        }
    }

    public function addSeminar(Seminar $seminar,Speaker $speaker)
    {
        $title = $seminar->getTitle();
        $startTime = $seminar->getStartTime();
        $endTime = $seminar->getEndTime();
        $description = $seminar->getDescription();
        $placesAvailable = $seminar->getPlacesAvailable();
        $roomId = $seminar->getRoomId();

        $firstName = $speaker->getFirstName();
        $lastName = $speaker->getLastName();
        $email = $speaker->getEmail();
        $address = $speaker->getAddress();
        $institution = $speaker->getInstitution();

        // Query definition for adding values into the Seminar table
        $sql = "INSERT INTO Seminar VALUES(NULL,'$title','$startTime','$endTime','$description','$placesAvailable')";

        // Query definition for adding values into the Speaker table
        $sqlSpeaker = "INSERT INTO Speaker VALUES(NULL , '$firstName','$lastName','$email','$address','$institution')";

        // Query to find out the seminar Id and the speaker Id
        $sql3 = "SELECT MAX(seminarId) AS  seminarNo FROM Seminar";
        $sql4 = "SELECT MAX(speakerId) AS  speakerNo FROM Speaker";


        if(!($this->link->query($sql) ) )  // Actual querying to insert values into Seminar
        {
            die('There was an error running the query no 1 ' . $this->link->error);
        }
        else
        {
            echo "New seminar created successfully;". "<br>";
        }

        $maxResult = $this->link->query($sql3);
        $row = $maxResult->fetch_assoc();
        $maxSeminarNo = $row['seminarNo'];
        echo "MaxSem: ".$maxSeminarNo;

        // Query definition for assigning room number and seminar id into Allocated_Room table
        $sql2 = "INSERT INTO Allocated_Room VALUES ($maxSeminarNo,$roomId)";

        if(!($this->link->query($sql2) ) )  // Actual querying to insert values into Allocated Room
        {
            die('There was an error running the query no 2 ' . $this->link->error);
        }
        else
        {
            echo " Room allocated successfully;" . "<br/> ";
        }

        if(!($this->link->query($sqlSpeaker) ) )  // Actual querying to insert values into Speaker
        {
            die('There was an error running the query SpeakerSQL' . $this->link->error);
        }
        else
        {
            echo " New Speaker created successfully;" . "<br> ";
        }

        $maxResult2 = $this->link->query($sql4);
        $row2 = $maxResult2->fetch_assoc();
        $maxSpeakerNo = $row2['speakerNo'];
        echo "MaxSpe: ".$maxSpeakerNo;

        // Query definition for inserting the seminar no and speaker no into the table
        $sqlSeminarSpeaker = "INSERT INTO Seminar_has_Speaker VALUES ($maxSpeakerNo,$maxSeminarNo)";
        echo $sqlSeminarSpeaker;

        if(!($this->link->query($sqlSeminarSpeaker) ) ) // Actual querying to insert values into Seminar_has_Speaker
        {
            die('There was an error running the query SqlSeminarspeaker' . $this->link->error);
        }
        else
        {
            echo "Speaker assigned to the Seminar successfully;" . "<br>";
        }


        $maxResult->free();
        $this->link->close();

    }

    public function addAttendee(Attendee $attendee)
    {
        $firstName   = $attendee->getFirstName();
        $lastName    = $attendee->getLastName();
        $email       = $attendee->getEmail();
        $institution = $attendee->getInstitution();

        $seminarId =$_GET['seminarId'];
        $seminarId = (int) $seminarId;
        //echo "Seminar ID db: " . $seminarId . " <br>";

        //  Query definition to check wheter the person isn't already registered in the database

        $sqlCheckDuplicateAttendee = "SELECT Attendee.attendeeId FROM Attendee where firstName='$firstName' AND lastName='$lastName' AND email='$email'";
        $sqlDuplicateAttendee = $this->link->query($sqlCheckDuplicateAttendee);
        $isAlreadyRegistered = $sqlDuplicateAttendee->fetch_assoc();

        // Query definition to check whether the person who wants to register isn't already to the seminar registered based on their first and last name, his/her email, and the seminar to register for

        $sqlCheckDuplicateSeminar = "SELECT Attendee.attendeeId FROM Attendee
                                     JOIN Attendee_attends_Seminar USING (attendeeId)
                                     WHERE firstName='$firstName' AND lastName='$lastName' AND email='$email' AND Attendee_attends_Seminar.seminarId = $seminarId";
        $sqlDuplicateResult = $this->link->query($sqlCheckDuplicateSeminar);
        $isAlreadyRegisteredForSeminar = $sqlDuplicateResult->fetch_assoc();

        if(is_null($isAlreadyRegistered)) // If the person isn't already registered in the database
        {

            // Query definition to insert values into Attendee table

            $sqlInsertAttendee = "INSERT INTO Attendee VALUES (NULL ,'$firstName','$lastName','$email','$institution')";

            if (!($this->link->query($sqlInsertAttendee))) // Actual querying action of inserting values into Attendee table
            {
                die('There was an error running the query sqlInsertAttendee' . $this->link->error);
            } else {
                echo "<h2>Registration was successful!</h2>";
            }

            $sqlAttendeeNo = "SELECT MAX(attendeeId) AS  attendeeNo FROM Attendee";
            $attendeeNoResult = $this->link->query($sqlAttendeeNo);
            $row = $attendeeNoResult->fetch_assoc();
            $attendeeNo = $row['attendeeNo'];
            //echo " Att no: ".$attendeeNo;


            // Query definition for assigning a ticket id and the rest of the values into the Attendee_attends_Seminar table

            $sqlAssignTicketNo = "INSERT INTO Attendee_attends_Seminar VALUES (NULL,$attendeeNo,$seminarId)";
            //echo $sqlAssignTicketNo;

            if (!($this->link->query($sqlAssignTicketNo))) {
                die('There was an error running the query sqlAssignTicket' . $this->link->error);
            } else {
                echo "<h2>The ticket assigning was successful!</h2>";
            }

            // The code to retrieve all the details for the ticket printing

            $sqlTicketNo = "SELECT MAX(ticketId) AS ticketNo FROM Attendee_attends_Seminar";
            $ticketNoResult = $this->link->query($sqlTicketNo);
            $row3 = $ticketNoResult->fetch_assoc();
            $ticketNo = $row3['ticketNo'];
            echo " Your ticket no is : " . $ticketNo;

            $sqlSeminarDetails = "SELECT DISTINCT title,startTime,description,Room.roomName,Room.location from Seminar JOIN Allocated_Room USING (seminarId) JOIN Room USING (roomId) where seminarId =$seminarId";
            $seminarDetailsResult = $this->link->query($sqlSeminarDetails);
            $row2 = $seminarDetailsResult->fetch_assoc();
            $seminarTitle = $row2['title'];
            $startTime = $row2['startTime'];
            $description = $row2['description'];
            $roomName = $row2['roomName'];
            $location = $row2['location'];


            // The code for displaying the ticket

            echo "<br><br><h2> The following ticket will be your proof of registration: </h2><br><br><br>";

            echo '<table id="tableTicket">
                    <tr>
                         <td id="seminarNo" rowspan="4"><h2>Seminar No: ' . $seminarId . '</h2></td>
                         <td id="names"> ' . $firstName . ' ' . $lastName . '</td>
                         <td id="ticketId" rowspan="4"><h2>Ticket Id:<br> ' . $ticketNo . ' </h2></td>
                    </tr>

                    <tr>
                         <td id="title">' . $seminarTitle . ' </td>
                    </tr>

                    <tr>
                         <td id="description"> ' . $description . ' </td>
                    </tr>

                    <tr>
                        <td id="locationAndTime"> ' . $roomName . ' - ' . $location . ' <br> Starting Time and Date : ' . $startTime . '</td>
                    </tr>
             </table>';

            // The link to withdrawal page

            echo "</br>";
            echo "<h2>If you'd like to unregister from the Seminar, please click the following link:</h2><br>";
            echo "<a href='withdraw.php?firstName=$firstName&lastName=$lastName&email=$email&institution=$institution&seminarId=$seminarId'>  <input type='button' value='Unregister'/>  </a>" ;

        }
        else
        {
            echo " <br> You are already registered !";
            if(is_null($isAlreadyRegisteredForSeminar))
            {

                $sqlAttendeeNo = "SELECT MAX(attendeeId) AS  attendeeNo FROM Attendee";
                $attendeeNoResult = $this->link->query($sqlAttendeeNo);
                $row = $attendeeNoResult->fetch_assoc();
                $attendeeNo = $row['attendeeNo'];
                //echo " Att no: ".$attendeeNo;


                // Query definition for assigning a ticket id and the rest of the values into the Attendee_attends_Seminar table

                $sqlAssignTicketNo = "INSERT INTO Attendee_attends_Seminar VALUES (NULL,$attendeeNo,$seminarId)";
                //echo $sqlAssignTicketNo;

                if (!($this->link->query($sqlAssignTicketNo))) {
                    die('There was an error running the query sqlAssignTicket' . $this->link->error);
                } else {
                    echo "<h2>The ticket assigning was successful!</h2>";
                }

                // The code to retrieve all the details for the ticket printing

                $sqlTicketNo = "SELECT MAX(ticketId) AS ticketNo FROM Attendee_attends_Seminar";
                $ticketNoResult = $this->link->query($sqlTicketNo);
                $row3 = $ticketNoResult->fetch_assoc();
                $ticketNo = $row3['ticketNo'];
                echo " Your ticket no is : " . $ticketNo;

                $sqlSeminarDetails = "SELECT DISTINCT title,startTime,description,Room.roomName,Room.location from Seminar JOIN Allocated_Room USING (seminarId) JOIN Room USING (roomId) where seminarId =$seminarId";
                $seminarDetailsResult = $this->link->query($sqlSeminarDetails);
                $row2 = $seminarDetailsResult->fetch_assoc();
                $seminarTitle = $row2['title'];
                $startTime = $row2['startTime'];
                $description = $row2['description'];
                $roomName = $row2['roomName'];
                $location = $row2['location'];


                // The code for displaying the ticket

                echo "<br><br><h2> The following ticket will be your proof of registration: </h2><br><br><br>";

                echo '<table id="tableTicket">
                    <tr>
                         <td id="seminarNo" rowspan="4"><h2>Seminar No: ' . $seminarId . '</h2></td>
                         <td id="names"> ' . $firstName . ' ' . $lastName . '</td>
                         <td id="ticketId" rowspan="4"><h2>Ticket Id:<br> ' . $ticketNo . ' </h2></td>
                    </tr>

                    <tr>
                         <td id="title">' . $seminarTitle . ' </td>
                    </tr>

                    <tr>
                         <td id="description"> ' . $description . ' </td>
                    </tr>

                    <tr>
                        <td id="locationAndTime"> ' . $roomName . ' - ' . $location . ' <br> Starting Time and Date : ' . $startTime . '</td>
                    </tr>
             </table>';

                // The link to withdrawal page

                echo "</br>";
                echo "<h2>If you'd like to unregister from the Seminar, please click the following link:</h2><br>";
                echo "<a href='withdraw.php?firstName=$firstName&lastName=$lastName&email=$email&institution=$institution&seminarId=$seminarId'>  <input type='button' value='Unregister'/>  </a>" ;


            }
            else

            {
                echo "<h2>You are already registered for this seminar !</h2>";
            }
        }






    }

    public function withdrawAttendee(Attendee $attendee,$seminarId)
    {
        $firstName   = $attendee->getFirstName();
        $lastName    = $attendee->getLastName();
        $email       = $attendee->getEmail();
        $institution = $attendee->getInstitution();


        $sqlTicketId = "SELECT DISTINCT Attendee_attends_Seminar.ticketId,Attendee_attends_Seminar.seminarId FROM Attendee JOIN Attendee_attends_Seminar on Attendee.attendeeId = Attendee_attends_Seminar.attendeeId
                             WHERE Attendee.email = '$email' AND Attendee_attends_Seminar.seminarId = '$seminarId';";


        $ticketIdResult = $this->link->query($sqlTicketId);
        $row = $ticketIdResult->fetch_assoc();
        $ticketId = $row['ticketId'];

        $sqlDeleteTicket ="DELETE FROM Attendee_attends_Seminar WHERE ticketId=$ticketId";
        if (!($this->link->query($sqlDeleteTicket)))
        {
            die('There was an error running the query sqlDeleteTicket' . $this->link->error);
        }
        else
        {
            echo "Deleted ticket number successfully!";
        }

    }

    public function verifyOrganiser(seminarOrganiser $organiser)
    {
        $login_name = $organiser->getLoginName();
        $password   = $organiser->getPassword();

        $truth = false;

        $sqlVerification = "SELECT password FROM Seminar_Organiser where login_name = '$login_name'";
        $loginDetails = $this->link->query($sqlVerification);
        $row = $loginDetails->fetch_assoc();
        $pass = $row['password'];

        if($password === $pass)
        {
            $truth = true;
        }
        else;

        return $truth;
    }

    public function displaySeminar(seminarOrganiser $organiser)
    {
        $login_name = $organiser->getLoginName();

        $sqlGetSeminars = "SELECT Seminar.seminarId,
                           Seminar.title,
                           Seminar_Organiser.firstName,
                           Seminar_Organiser.lastName,
                           Seminar.startTime,
                           Seminar.endTime,
                           Seminar.description,
                           Seminar.placesAvailable,
                           COUNT(Seminar_Organiser_has_Seminar.seminarId) as placesFilledSoFar

                           FROM Seminar_Organiser_has_Seminar JOIN Seminar_Organiser USING (login_name)
                             JOIN Seminar USING (seminarId) LEFT JOIN Attendee_attends_Seminar USING (seminarId)
                             WHERE Seminar_Organiser.login_name='$login_name'
                             GROUP BY Attendee_attends_Seminar.seminarId";

        $seminarDetails = $this->link->query($sqlGetSeminars);
        //echo $seminarDetails->num_rows;
        while($row = $seminarDetails->fetch_assoc())
        {
            echo '<table class="regForm">
                        <thead><h2><b>Seminar No: '. $row['seminarId'] .'</b></h2>  </thead>
                              <tr> <td>Seminar Id: </td><td> '.$row['seminarId'] . ' </td></tr>
                              <tr> <td>Seminar Title: </td> <td> '.$row['title'] . ' </td></tr>
                              <tr> <td>Organiser\'s First Name: </td> <td>'.$row['firstName'] . '</td></tr>
                              <tr> <td>Organiser\'s Last Name: </td> <td>'.$row['lastName'] . '</td></tr>
                              <tr> <td>Seminar Starting Time: </td> <td>'.$row['startTime'] . '</td></tr>
                              <tr> <td>Seminar Ending Time: </td> <td>'.$row['startTime'] . '</td></tr>
                              <tr> <td>Seminar Description: </td> <td>'.$row['description'] . '</td></tr>
                              <tr> <td>Places Available: </td> <td>'.$row['placesAvailable'] . '</td></tr>
                              <tr> <td>Places Filled so Far: </td> <td>'.$row['placesFilledSoFar'] . '</td></tr>
                  </table>';
        }




    }

    public function verifyManager(Centre_Manager $manager)
    {
        $login = $manager->getLogin();
        $password   = $manager->getPassword();

        $truth = false;

        $sqlVerification = "SELECT password FROM Centre_Management where login = '$login'";
        $loginDetails = $this->link->query($sqlVerification);
        $row = $loginDetails->fetch_assoc();
        $pass = $row['password'];

        if($password === $pass)
        {
            $truth = true;
        }
        else;

        return $truth;

    }

    public function displayOverview()
    {
        $sqlGetSeminars = "SELECT Seminar.seminarId,Seminar.title,Seminar.description,startTime,endTime FROM Seminar WHERE startTime < curdate()";


        $seminarDetails = $this->link->query($sqlGetSeminars);
        //echo $seminarDetails->num_rows;
        echo "</br><h2><b> The following are past seminars: </b></h2> ";
        while($row = $seminarDetails->fetch_assoc())
        {
            echo '<div class="divStyle"><table class="regForm">
                        <thead><h2><b>Seminar No: '. $row['seminarId'] .'</b></h2>  </thead>
                              <tr> <td>Seminar Id: </td><td> '.$row['seminarId'] . ' </td></tr>
                              <tr> <td>Seminar Title: </td> <td> '.$row['title'] . ' </td></tr>
                              <tr> <td>Seminar Starting Time: </td> <td>'.$row['startTime'] . '</td></tr>
                              <tr> <td>Seminar Ending Time: </td> <td>'.$row['startTime'] . '</td></tr>
                  </table>
                  </div>';
        }

        $sqlGetSeminars2 = "SELECT Seminar.seminarId,Seminar.title,Seminar.description,startTime,endTime FROM Seminar WHERE startTime > curdate()";


        $seminarDetails2 = $this->link->query($sqlGetSeminars2);
        //echo $seminarDetails->num_rows;
        echo "</br><h2 style='float: left;'><b> The following are upcoming seminars: </b></h2> ";
        while($row = $seminarDetails2->fetch_assoc())
        {
            echo '<div class="divStyle"><table class="regForm">
                        <thead><h2><b>Seminar No: '. $row['seminarId'] .'</b></h2>  </thead>
                              <tr> <td>Seminar Id: </td><td> '.$row['seminarId'] . ' </td></tr>
                              <tr> <td>Seminar Title: </td> <td> '.$row['title'] . ' </td></tr>
                              <tr> <td>Seminar Starting Time: </td> <td>'.$row['startTime'] . '</td></tr>
                              <tr> <td>Seminar Ending Time: </td> <td>'.$row['startTime'] . '</td></tr>
                  </table>
                  </div>';
        }

        $sqlGetRoomPopularity = "SELECT Room.roomId,COUNT(DISTINCT Allocated_Room.seminarId) as seminarsHosted FROM Allocated_Room JOIN Room USING (roomId) GROUP BY Room.roomId;";


        $roomPopularity = $this->link->query($sqlGetRoomPopularity);
        //echo $seminarDetails->num_rows;
        echo "</br><h2 style='float: left;'><b> Room popularity: </b></h2> ";
        while($row = $roomPopularity->fetch_assoc())
        {
            echo '<div class="divStyle"><table class="regForm">
                        <thead><h2><b>Room No: '. $row['roomId'] .'</b></h2>  </thead>
                              <tr> <td>Seminars Hosted </td><td> '.$row['seminarsHosted'] . ' </td></tr>

                  </table>
                  </div>';
        }

    }



    public function getLink()
    {
        return $this->link;
    }
}