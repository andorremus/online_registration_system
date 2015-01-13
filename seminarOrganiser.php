<?php


class seminarOrganiser
{
    private $login_name;
    private $password;
    /*private $firstName;
    private $lastName;*/

    public function __construct($login_name,$password)
    {
        $this->login_name = $login_name;
        $this->password = $password;
       /* $this->firstName = $firstName;
        $this->lastName = $lastName;*/
    }

    public function getLoginName()
    {
        return $this->login_name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /*public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }*/



}