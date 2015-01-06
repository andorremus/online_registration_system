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

        // Query definition to insert values into Attendee table
        $sqlInsertAttendee = "INSERT INTO Attendee VALUES (NULL ,'$firstName','$lastName','$email','$institution')";

        if(!($this->link->query($sqlInsertAttendee))) // Actual querying action of inserting values into Attendee table
        {
            die('There was an error running the query sqlInsertAttendee' . $this->link->error);
        }
        else
        {
            echo "Attendee created successfully!";
        }

        $seminarId =$_GET['seminarId'];
        $seminarId = (int) $seminarId;
        echo "Seminar ID db: " . $seminarId;

        $sqlAttendeeNo = "SELECT MAX(attendeeId) AS  attendeeNo FROM Attendee";
        $attendeeNoResult = $this->link->query($sqlAttendeeNo);
        $row = $attendeeNoResult->fetch_assoc();
        $attendeeNo = $row['attendeeNo'];
        echo " Att no: ".$attendeeNo;


        // Query definition for assigning a ticket id and the rest of the values into the Attendee_attends_Seminar table
        $sqlAssignTicketNo = "INSERT INTO Attendee_attends_Seminar VALUES (NULL,$attendeeNo,$seminarId)";
        echo $sqlAssignTicketNo;

        if(!($this->link->query($sqlAssignTicketNo)))
        {
            die('There was an error running the query sqlAssignTicket' . $this->link->error);
        }
        else
        {
            echo "Attendee assigned ticket number successfully!";
        }







    }

    public function getLink()
    {
        return $this->link;
    }
}