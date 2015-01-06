<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 04/01/2015
 * Time: 11:47
 */

class Speaker
{
    private $firstName;
    private $lastName;
    private $email;
    private $address;
    private $institution;

    public function __construct($firstName,$lastName,$email,$address,$institution)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->address = $address;
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getInstitution()
    {
        return $this->institution;
    }



}