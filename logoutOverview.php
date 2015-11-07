<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 18/01/2015
 * Time: 06:24
 */

session_start();
unset($_SESSION["logged_in_2"]);
unset($_SESSION["login"]);
unset($_SESSION['pass']);
header("Location:overview.php");


?>