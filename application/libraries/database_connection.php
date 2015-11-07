<?php

class database_connection
{
    function __construct()
    {
        $this->load->libraries('Attendee');
        $this->load->libraries('Attendee');
    }

    function add_attendee(Attendee $attendee)
    {
        $firstName   = $attendee->getFirstName();
        $lastName    = $attendee->getLastName();
        $email       = $attendee->getEmail();
        $institution = $attendee->getInstitution();

        $sqlInsertAttendee = "INSERT INTO Attendee VALUES (NULL ,'$firstName','$lastName','$email','$institution')";




    }

}