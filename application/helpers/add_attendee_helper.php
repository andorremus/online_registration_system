<?php

function addAttendee(Attendee $attendee)
{
    $firstName = $attendee->getFirstName();
    $lastName = $attendee->getLastName();
    $email = $attendee->getEmail();
    $institution = $attendee->getInstitution();

    $seminarId = $_GET['seminarId'];
    $seminarId = (int)$seminarId;
//echo "Seminar ID db: " . $seminarId . " <br>";

//  Query definition to check wheter the person isn't already registered in the database

    $sqlCheckDuplicateAttendee = "SELECT Attendee.attendeeId FROM Attendee where firstName='$firstName' AND lastName='$lastName' AND email='$email'";
    $sqlDuplicateAttendee = $this->link->query($sqlCheckDuplicateAttendee);
    $isAlreadyRegistered = $sqlDuplicateAttendee->fetch_assoc();

// Query definition to check whether the person who wants to register isn't already to the seminar registered based on their first and last name, his/her email, and the seminar to register for

    $sqlCheckDuplicateSeminar = "SELECT Attendee.attendeeId FROM Attendee JOIN Attendee_attends_Seminar USING (attendeeId) WHERE firstName='$firstName' AND lastName='$lastName'
                                                                                                   AND email='$email' AND Attendee_attends_Seminar.seminarId = $seminarId";
    $sqlDuplicateResult = $this->link->query($sqlCheckDuplicateSeminar);
    $isAlreadyRegisteredForSeminar = $sqlDuplicateResult->fetch_assoc();

    if (is_null($isAlreadyRegistered)) // If the person isn't already registered in the database
    {

// Query definition to insert values into Attendee table

        $sqlInsertAttendee = "INSERT INTO Attendee VALUES (NULL ,'$firstName','$lastName','$email','$institution')";

        if (!($this->link->query($sqlInsertAttendee))) // Actual querying action of inserting values into Attendee table
        {
            die('There was an error running the query sqlInsertAttendee' . $this->link->error);
        } else {
//echo "<h2>Registration was successful!</h2>";
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
//echo "<h2>The ticket assigning was successful!</h2>";
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
        <td id="seminarNo" rowspan="5"><h2>Seminar No: ' . $seminarId . '</h2></td>
        <td id="names"> ' . $firstName . ' ' . $lastName . '</td>
        <td id="ticketId" rowspan="5"><h2>Ticket Id:<br> ' . $ticketNo . ' </h2></td>
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

    <tr>
        <td>
            You can unsubscribe by accessing the following link: <br>
            http://www.computing.northampton.ac.uk/~13430492/CSY2028/assign1/withdraw.php?firstName=' . $firstName . '&lastName=' . $lastName . '&email=' . $email . '&institution=' . $institution . '&seminarId=' . $seminarId . '>
        </td>
    </tr>
</table>';

// The link to withdrawal page

        echo "</br>";
        echo "<h2>If you'd like to unregister from the Seminar, please click the following link:</h2><br>";
        echo "<a href='withdraw.php?firstName=$firstName&lastName=$lastName&email=$email&institution=$institution&seminarId=$seminarId'>  <input type='button' value='Unregister'/>  </a>";

    }
    else
    {
        echo " <br> You are already registered !";
        if (is_null($isAlreadyRegisteredForSeminar)) // Check if the person is already registered for the seminar
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
            echo "<a href='withdraw.php?firstName=$firstName&lastName=$lastName&email=$email&institution=$institution&seminarId=$seminarId'>  <input type='button' value='Unregister'/>  </a>";


        }
        else {
            echo "<h2>You are already registered for this seminar !</h2>";
        }
    }
}
?>
