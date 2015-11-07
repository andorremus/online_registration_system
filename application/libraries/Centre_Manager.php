<?php

class Centre_Manager
{
    private $login;
    private $password;

    public function __construct($login,$password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }





}