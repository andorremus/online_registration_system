<?php


class Seminar_Organiser
{
    private $login_name;
    private $password;

    public function __construct($login_name,$password)
    {
        $this->login_name = $login_name;
        $this->password = $password;

    }

    public function getLoginName()
    {
        return $this->login_name;
    }

    public function getPassword()
    {
        return $this->password;
    }


}