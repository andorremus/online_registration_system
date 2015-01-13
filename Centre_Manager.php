<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 10/01/2015
 * Time: 18:12
 */

class Centre_Manager
{
    private $login;
    private $password;
    private $firstName;
    private $lastName;
    private $email;

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