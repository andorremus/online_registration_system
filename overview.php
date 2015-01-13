<!DOCTYPE html>
<html>
<head lang="en">
    <style>
        .regForm
        {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 10px;
            margin-left: 30px;
        }
        td
        {
            border: solid 1px #000000;
        }
        .divStyle
        {
            float: left;
        }



    </style>


<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 10/01/2015
 * Time: 18:10
 */

require_once('databaseConnection.php');
require_once('Centre_Manager.php');

session_start();

if(isset($_POST['submit']))
{
    $host = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbName = "CSY2028_13430492";
    $dbConnection = new databaseConnection($host, $username, $password, $dbName);
    $_SESSION['logged_in'] = false;

    $login = $_POST['login'];
    $login  = mysqli_escape_string($dbConnection->getLink(),$login);

    $password = $_POST['password'];

    $manager = new Centre_Manager($login,$password);
    if($dbConnection->verifyManager($manager))
    {
        echo "Login details are correct, Welcome ". $manager->getLogin() . " .<br> ";
        $_SESSION['logged_in'] = true;
        $dbConnection->displayOverview();
    }
    else
    {
        echo "Login details are incorrect!";
        header("Location:overview.php");
    }


}
else {


    ?>


    <meta charset="UTF-8">
    <title>Seminar Overview - Login Script</title>

</head>

<body>

<form action="overview.php" method="post">
    <table class="regForm">

        <caption><b>Centre Management Login : <b></caption>
        <tr>
            <td>Login Name :</td>
            <td><input type="text" name="login" value=""></td>
        </tr>

        <tr>
            <td>Password :</td>
            <td><input type="password" name="password" value=""></td>
        </tr>

        <tr>
            <td><input type="submit" value="Login" name="submit"></td>
        </tr>

    </table>


</form>


</body>

<?php


}




?>

</html>