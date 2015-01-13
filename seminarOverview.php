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



    </style>

<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 08/01/2015
 * Time: 16:26
 */

require_once('databaseConnection.php');
require_once('seminarOrganiser.php');
session_start();

if(isset($_POST['submit']) )
{
    $host = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbName = "CSY2028_13430492";
    $dbConnection = new databaseConnection($host, $username, $password, $dbName);
    $_SESSION['logged_in'] = false;
    $_SESSION['loginName'] = $_POST['loginName'];
    $_SESSION['password']  = $_POST['password'];

    $login_name = $_POST['loginName'];
    $login_name  = mysqli_escape_string($dbConnection->getLink(),$login_name);

    $password = $_POST['password'];

    $seminarOrganiser = new seminarOrganiser($login_name,$password);
    if($dbConnection->verifyOrganiser($seminarOrganiser) )
    {
        echo "Login details are correct, Welcome ". $seminarOrganiser->getLoginName() . " .<br> ";
        $_SESSION['logged_in'] = true;
        echo "</br> <b> The following seminars are organised by you : </b>";
        $dbConnection->displaySeminar($seminarOrganiser);
    }
    else
    {
        echo "Login details are incorrect!";
        header("Location:seminarOverview.php");
    }


}

else
{




?>



    <meta charset="UTF-8">
    <title>Seminar Overview - Login Script</title>

</head>

<body>

<form action="seminarOverview.php" method="post">
    <table class="regForm">

        <caption><b>Seminar Organiser Login: <b></caption>
        <tr>
            <td>Login Name :</td>
            <td><input type="text" name="loginName" value=""></td>
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
