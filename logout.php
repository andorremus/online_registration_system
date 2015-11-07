<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 17/01/2015
 * Time: 22:53
 */

session_start();
unset($_SESSION["logged_in"]);
unset($_SESSION["loginName"]);
unset($_SESSION['password']);
header("Location:seminarOverview.php");

?>