<?php
include_once("../a2/application/libraries/Attendee.php");
include_once("../a2/application/libraries/Seminar.php");
include_once("../a2/application/libraries/Speaker.php");

class registration_center_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function add_attendee(Attendee $attendeeToAdd,$seminarId)
    {
        $IS_IN_DB = TRUE;    // TO recognize the values easier when calling the insert attendee method
        $NOT_IN_DB = FALSE;

        $already_in_db = $this->is_attendee_in_db($attendeeToAdd);
        $already_registered_for_seminar = $this->is_attendee_registered_for_seminar($attendeeToAdd,$seminarId);
        $place_available = $this->is_place_available($seminarId);
        //echo $place_available;

        if ($already_in_db)   // If the user is already in the db
        {
            if ($already_registered_for_seminar) // If the user is already registered for the seminar
            {
                $result = 'ALREADY_IN_SEMINAR';
            }
            else
            {
                if($place_available) // If there are places available
                {
                    if ($this->insert_attendee($attendeeToAdd, $seminarId, $IS_IN_DB))
                    {
                        $result = "REGISTERED_SUCCESSFULLY";
                    }
                    else
                    {
                        $result = "ERROR";
                    }
                }
                else  // If there are no places available in seminar
                {
                    $result = "NO_PLACES_AVAILABLE";
                }

            }
        }
        else   // If the user is not in the db yet
        {
            if($place_available) // If there are places available
            {
                $this->insert_attendee($attendeeToAdd, $seminarId, $NOT_IN_DB);
                $result = "REGISTERED_SUCCESSFULLY";
            }
            else
            {
                $result = "NO_PLACES_AVAILABLE";
            }
        }
        return $result;
    }


    function is_attendee_in_db(Attendee $attendee)
    {
        $firstName = $attendee->getFirstName();
        $lastName = $attendee->getLastName();
        $email = $attendee->getEmail();

        $sqlCheckDuplicateAttendee = "SELECT Attendee.attendeeId FROM Attendee where firstName='$firstName' AND lastName='$lastName' AND email='$email'";
        $sqlDuplicateAttendee = $this->db->query($sqlCheckDuplicateAttendee);
        $isAlreadyRegistered = $sqlDuplicateAttendee->_fetch_assoc();

        if ($isAlreadyRegistered)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    function is_place_available($seminarId)
    {
        $result = false;
        $sqlCheckPlaces = "SELECT DISTINCT COUNT(DISTINCT Attendee_attends_Seminar.attendeeId) as placesFilled,Seminar.placesAvailable
                            FROM Attendee_attends_Seminar JOIN Seminar USING  (seminarId) WHERE Attendee_attends_Seminar.seminarId =$seminarId";
        $sqlPlacesResult = $this->db->query($sqlCheckPlaces);
        $row = $sqlPlacesResult->_fetch_assoc();
        $placesFilled = $row['placesFilled'];
        $placesAvailable = $row['placesAvailable'];
        $placesFilled= (int) $placesFilled;
        $placesAvailable= (int) $placesAvailable;

        if($placesFilled < $placesAvailable)
        {
            $result=true;
        }
        return $result;
    }

    function is_attendee_registered_for_seminar(Attendee $attendee,$seminarId)
    {
        $firstName = $attendee->getFirstName();
        $lastName = $attendee->getLastName();
        $email = $attendee->getEmail();

        $sqlCheckDuplicateSeminar = "SELECT Attendee.attendeeId FROM Attendee
                                     JOIN Attendee_attends_Seminar USING (attendeeId)
                                     WHERE firstName='$firstName' AND lastName='$lastName' AND email='$email' AND Attendee_attends_Seminar.seminarId = $seminarId";
        $sqlDuplicateResult = $this->db->query($sqlCheckDuplicateSeminar);
        $isAlreadyRegisteredForSeminar = $sqlDuplicateResult->_fetch_assoc();

        if ($isAlreadyRegisteredForSeminar) {
            return true;
        } else {
            return false;
        }
    }

    function insert_attendee(Attendee $attendee, $seminarId,$already_in_db)
    {
        $result = false;
        $firstName = $attendee->getFirstName();
        $lastName = $attendee->getLastName();
        $email = $attendee->getEmail();
        $institution = $attendee->getInstitution();

        if(!$already_in_db) // if the user is not in the database at all
        {
            $sqlInsertAttendee = "INSERT INTO Attendee VALUES (NULL ,'$firstName','$lastName','$email','$institution')";

            if (!($this->db->query($sqlInsertAttendee))) // Actual querying action of inserting values into Attendee table
            {
                die('There was an error running the query sqlInsertAttendee' . $this->db->display_error('Insert Attendee'));
            } else
            {
                $result = true;
            }

            $sqlAttendeeNo = "SELECT MAX(attendeeId) AS  attendeeNo FROM Attendee";
            $attendeeNoResult = $this->db->query($sqlAttendeeNo);
            $row = $attendeeNoResult->_fetch_assoc();
            $attendeeNo = $row['attendeeNo'];

            // Query definition for assigning a ticket id and the rest of the values into the Attendee_attends_Seminar table

            $sqlAssignTicketNo = "INSERT INTO Attendee_attends_Seminar VALUES (NULL,$attendeeNo,$seminarId)";
            //echo $sqlAssignTicketNo;

            if (!($this->db->query($sqlAssignTicketNo)))
            {
                die('There was an error running the query sqlAssignTicket' . $this->db->display_error('Assign ticket no'));
            } else
            {
                $result = true;
            }
        }
        else
        {
            $sqlAttendeeNo = "SELECT attendeeId FROM Attendee WHERE email = '$email'";
            $attendeeNoResult = $this->db->query($sqlAttendeeNo);
            $row = $attendeeNoResult->_fetch_assoc();
            $attendeeNo = $row['attendeeId'];

            // Query definition for assigning a ticket id and the rest of the values into the Attendee_attends_Seminar table

            $sqlAssignTicketNo = "INSERT INTO Attendee_attends_Seminar VALUES (NULL,$attendeeNo,$seminarId)";
            //echo $sqlAssignTicketNo;

            if (!($this->db->query($sqlAssignTicketNo)))
            {
                die('There was an error running the query sqlAssignTicket' . $this->db->display_error('Assign ticket no'));
            }
            else
            {
                $result=true;
            }

        }

        return $result;
    }

    function withdraw_attendee($email,$seminarId)
    {
        $result = false;
        $sqlAttendeeId = "SELECT Attendee.attendeeId from Attendee
                            JOIN Attendee_attends_Seminar using (attendeeId)
                            WHERE Attendee.email ='$email' and Attendee_attends_Seminar.seminarId = $seminarId";
        $sqlAttendeeIdResult = $this->db->query($sqlAttendeeId);
        $attendeeIdArray = $sqlAttendeeIdResult->_fetch_assoc();
        $attendeeId = $attendeeIdArray['attendeeId'];
        $attendeeId = (int) $attendeeId;

        //echo $email;
        //echo $attendeeId;
        //echo $seminarId;

        $sqlDeleteAttendeeFromSeminar = "delete from Attendee_attends_Seminar where attendeeId= $attendeeId and seminarId = $seminarId";

        if (!($this->db->query($sqlDeleteAttendeeFromSeminar))) // Actual querying action of inserting values into Attendee table
        {
            die('There was an error running the query sqlDeleteAttendee' . $this->db->error);

        } else
        {
            $result = true;
        }

        return $result;

    }


    function retrieve_seminars()
    {
        $sqlSeminars = "select seminarId from Seminar where startTime > now() and placesAvailable > 0";
        $seminars = $this->db->query($sqlSeminars);
        $seminarsArray = $seminars->result_array();
        return $seminarsArray;
    }


    function add_seminar(Seminar $seminar,Speaker $speaker)
    {
        $title           = $seminar->getTitle();
        $startTime       = $seminar->getStartTime();
        $endTime         = $seminar->getEndTime();
        $description     = $seminar->getDescription();
        $roomId          = $seminar->getRoomId();
        $placesAvailable = $seminar->getPlacesAvailable();

        $firstName       = $speaker->getFirstName();
        $lastName        = $speaker->getLastName();
        $email           = $speaker->getEmail();
        $address         = $speaker->getAddress();
        $institution     = $speaker->getInstitution();

        // Query definition for adding values into the Seminar table
        $sql = "INSERT INTO Seminar VALUES(NULL,'$title','$startTime','$endTime','$description','$placesAvailable')";

        // Query definition for adding values into the Speaker table
        $sqlSpeaker = "INSERT INTO Speaker VALUES(NULL , '$firstName','$lastName','$email','$address','$institution')";

        // Query to find out the seminar Id and the speaker Id
        $sql3 = "SELECT MAX(seminarId) AS  seminarNo FROM Seminar";
        $sql4 = "SELECT MAX(speakerId) AS  speakerNo FROM Speaker";


        if(!($this->db->query($sql) ) )  // Actual querying to insert values into Seminar
        {
            die('There was an error running the query no 1 ' . $this->db->display_error());
        }
        else
        {
           // echo "New seminar created successfully;". "<br>";
        }

        $maxResult = $this->db->query($sql3);
        $row = $maxResult->_fetch_assoc();
        $maxSeminarNo = $row['seminarNo'];
        //echo "MaxSem: ".$maxSeminarNo;

        // Query definition for assigning room number and seminar id into Allocated_Room table
        $sql2 = "INSERT INTO Allocated_Room VALUES ($maxSeminarNo,$roomId)";

        if(!($this->db->query($sql2) ) )  // Actual querying to insert values into Allocated Room
        {
            die('There was an error running the query no 2 ' . $this->db->display_error());
        }
        else
        {
            //echo " Room allocated successfully;" . "<br/> ";
        }

        if(!($this->db->query($sqlSpeaker) ) )  // Actual querying to insert values into Speaker
        {
            die('There was an error running the query SpeakerSQL' . $this->db->error);
        }
        else
        {
            //echo " New Speaker created successfully;" . "<br> ";
        }

        $maxResult2 = $this->db->query($sql4);
        $row2 = $maxResult2->_fetch_assoc();
        $maxSpeakerNo = $row2['speakerNo'];
        //echo "MaxSpe: ".$maxSpeakerNo;

        // Query definition for inserting the seminar no and speaker no into the table
        $sqlSeminarSpeaker = "INSERT INTO Seminar_has_Speaker VALUES ($maxSpeakerNo,$maxSeminarNo)";
        //echo $sqlSeminarSpeaker;

        if(!($this->db->query($sqlSeminarSpeaker) ) ) // Actual querying to insert values into Seminar_has_Speaker
        {
            die('There was an error running the query SqlSeminarspeaker' . $this->db->display_error());
        }
        else
        {
            //echo "Speaker assigned to the Seminar successfully;" . "<br>";
        }

        $sqlFindSeminar = "SELECT max(seminarId) AS semId FROM Seminar";     // Get the seminar that was just created and use it for displaying the registration link
        //echo $sqlFindSeminar;
        $seminarAdded = $this->db->query($sqlFindSeminar);
        //echo $seminarAdded;
        $seminarArray = $seminarAdded->_fetch_assoc();

        return $seminarArray['semId'];




    }

    function check_room_availability(Seminar $seminar)
    {
        $startTime = $seminar->getStartTime();
        $endTime   = $seminar->getEndTime();
        $roomId    = $seminar->getRoomId();

        $sqlCheckAvailability = "SELECT * from Seminar INNER JOIN Allocated_Room USING (seminarId) where '$startTime' <= endTime AND '$endTime' >= startTime and Allocated_Room.roomId=$roomId";
        $roomAvailableResult = $this->db->query($sqlCheckAvailability);
        $roomAvailability = $roomAvailableResult->_fetch_assoc();

        return $roomAvailability;




    }

    function get_room_details()
    {
        $sqlRoomDetails ="SELECT * from Room";                     // Define the query and retrieve the room capacities
        $roomDetails = $this->db->query($sqlRoomDetails);

        $result = $roomDetails->result_array();

        return $result;
    }

    function get_ticket_details($email,$seminarId)
    {
        $sqlTicketDetails ="SELECT DISTINCT * from Attendee
                              JOIN Attendee_attends_Seminar USING (attendeeId)
                              JOIN Seminar USING (seminarId)
                              JOIN Allocated_Room USING (seminarId)
                              JOIN Room USING (roomId) where seminarId = $seminarId and email = '$email'";   // Define the query and retrieve the
        $ticketDetails = $this->db->query($sqlTicketDetails);                                       //  ticket details

        $result = $ticketDetails->result_array();

        return $result;

    }

    function validate_seminar_organiser_login(Seminar_Organiser $seminar_Organiser)
    {
        $result = false;

        $username = $seminar_Organiser->getLoginName();
        $password = $seminar_Organiser->getPassword();

        $sqlVerification = "SELECT password FROM Seminar_Organiser where login_name = '$username'";
        $loginDetails = $this->db->query($sqlVerification);
        $row = $loginDetails->_fetch_assoc();
        $pass = $row['password'];

        if($password === $pass)
        {
            $result = true;
        }
        else
        {
            // do nothing as the variable is already set to false
        }

        return $result;
    }

    function retrieve_organiser_seminars($username)
    {
        $sqlSeminars ="SELECT Seminar.seminarId,
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
                                    WHERE Seminar_Organiser.login_name='$username'
                                    GROUP BY Attendee_attends_Seminar.seminarId";                    // Define the query and retrieve the
        $seminars = $this->db->query($sqlSeminars);                                                  //  seminar details

        $result = $seminars->result_array();

        return $result;
    }

    function retrieve_seminars_stats($username)
    {
        $sqlStats ="SELECT COUNT(Attendee.institution) as noOfPeople,Attendee.institution
                          FROM Attendee JOIN Attendee_attends_Seminar USING (attendeeId)  JOIN Seminar USING (seminarId) JOIN Seminar_Organiser_has_Seminar USING (seminarId)
                          JOIN Seminar_Organiser USING (login_name) WHERE login_name='$username'
                          GROUP BY Attendee.institution ORDER BY seminarId";                    // Define the query and retrieve the
        $stats = $this->db->query($sqlStats);                                                  //  seminar details

        $result = $stats->result_array();

        return $result;


    }

    function validate_centre_manager_login(Centre_Manager $centreManager)
    {
        $result = false;

        $username = $centreManager->getLogin();
        $password = $centreManager->getPassword();

        $sqlVerification = "SELECT password FROM Centre_Management where login = '$username'";
        $loginDetails = $this->db->query($sqlVerification);
        $row = $loginDetails->_fetch_assoc();
        $pass = $row['password'];

        if($password === $pass)
        {
            $result = true;
        }
        else
        {
            // do nothing as the variable is already set to false
        }

        return $result;
    }

    function retrieve_past_seminars()
    {
        $sqlPastSeminars ="SELECT Seminar.seminarId,Seminar.title,Seminar.description,startTime,endTime,COUNT(Attendee_attends_Seminar.ticketId) as attendance
                            FROM Seminar JOIN Attendee_attends_Seminar USING (seminarId) WHERE startTime < curdate() GROUP BY Attendee_attends_Seminar.seminarId ORDER BY startTime ASC";                    // Define the query and retrieve the
        $pastSeminars = $this->db->query($sqlPastSeminars);
        $result = $pastSeminars->result_array();
        return $result;

    }

    function retrieve_upcoming_seminars()
    {
        $sqlUpcomingSeminars ="SELECT Seminar.seminarId,Seminar.title,Seminar.description,startTime,endTime,roomId as room
                                FROM Seminar join Allocated_Room using (seminarId) WHERE startTime > curdate()";                    // Define the query and retrieve the
        $upcomingSeminars = $this->db->query($sqlUpcomingSeminars);
        $result = $upcomingSeminars->result_array();
        return $result;
    }

    function retrieve_expected_attendance()
    {
        $sqlExpectedAttendance ="select Allocated_Room.roomId, (  COUNT(Attendee_attends_Seminar.ticketId) / Count( DISTINCT Attendee_attends_Seminar.seminarId) ) as average
                                      from Seminar join Allocated_Room using(seminarId) JOIN Attendee_attends_Seminar using(seminarId)
                                      where startTime < curdate()
                                      group by roomId";
        $expectedAttendance = $this->db->query($sqlExpectedAttendance);
        $result = $expectedAttendance->result_array();
        return $result;
    }

    function retrieve_room_popularity()
    {
        $sqlRoomPopularity ="SELECT Room.roomId,COUNT(DISTINCT Allocated_Room.seminarId) as seminarsHosted FROM Allocated_Room JOIN Room USING (roomId) GROUP BY Room.roomId";
        $roomPopularity = $this->db->query($sqlRoomPopularity);
        $result = $roomPopularity->result_array();
        return $result;
    }







    function show_details()
    {
        /*$routes = array(
            18=> array("0705", "0745", "0815", "0855", "0925", "0955", "1025", "1055", "1125", "1155", "1225", "1255", "1325", "1355", "1425", "1455", "1525", "1555", "1635", "1655"),
            19=> array("0733", "0813", "0843", "0923", "0953", "1023", "1053", "1123", "1153", "1223", "1253", "1323", "1353", "1423", "1453", "1523", "1553", "1623", "1703", "1723"),
            20=> array("0733", "0813", "0843", "0923", "0953", "1023", "1053", "1123", "1153", "1223", "1253", "1323", "1353", "1423", "1453", "1523", "1553", "1623", "1703", "1723")
        );*/
        $details = $this->db->get('addressbook');

        $detailsArray = array();
        /* while ($row = $details->_fetch_assoc())
         {
             $detailsArray['id'] = $row['id'];
             $detailsArray['firstname'] = $row['firstname'];
             $detailsArray['lastname'] = $row['lastname'];
             $detailsArray['address'] = $row['address'];
             $detailsArray['telephone'] = $row['telephone'];
         }*/
        $detailsArray = $details->result_array();

        /* foreach ($detailsArray as $value) {
             echo $value['id'];
             echo $value['firstname'];
             echo $value['lastname'];
             echo $value['address'];
             echo $value['telephone'];
             echo "<br>";
         }*/
    }
}
