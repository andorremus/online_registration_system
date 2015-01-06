<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 04/01/2015
 * Time: 11:38
 */

class Attendee
{
    private $firstName;
    private $lastName;
    private $email;
    private $institution;

    public function __construct($firstName,$lastName,$email,$institution)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->institution = $institution;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getInstitution()
    {
        return $this->institution;
    }

    public function getEmail()
    {
        return $this->email;
    }



}