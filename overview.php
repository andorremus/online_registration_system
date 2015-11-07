<!DOCTYPE html>
<html>
<head lang="en">
    <script type="text/javascript" src="javascript.js"></script>
    <link type="text/css" rel="stylesheet" href="stylesheet.css">
    <style>
        .regForm
        {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 10px;
            margin-left: 30px;
            border-radius: 10px;
        }
        td
        {
            border: solid 1px #000000;
        }
        .divStyle
        {
           display: inline-block;
        }

        #content /* Here starts the content */
{
            border:1px black solid;
            border-radius:10px;
            position: relative;
            text-align:left;
            display: block;
            width: auto;
            padding:5px 5px 5px 5px;
            height: auto;
            margin-top: 20px;
        }




    </style>

    <?php include_once("analyticstracking.php") ?>





    <meta charset="UTF-8">
    <title>Overview </title>

</head>


<body>
<div id="page"><!--Beginning of the page-->

    <div id="header"><!--This is the beginning of the header-->


        <a id="homelogo">homelogo </a>



        <a id="klogo">Klogo</a>

        <a id="date">
            <script type="text/javascript">
                new imageclock.displayDate();
                new imageclock.display();
            </script>
        </a>


    </div><!--This is the end of the header-->

    <div id="content"><!--Here's the content-->

        <?php
        /**
         * Created by PhpStorm.
         * User: Remus
         * Date: 10/01/2015
         * Time: 18:10
         */

        require_once('databaseConnection.php');  // Import the needed classes
        require_once('Centre_Manager.php');
        session_start();

        $host = "194.81.104.22";         // Create the variables needed for the connection and open it
        $username = "s13430492";
        $password = "remian10";
        $dbName = "CSY2028_13430492";
        $dbConnection = new databaseConnection($host, $username, $password, $dbName);

        if(isset($_SESSION['logged_in_2']))
        {
            $manager = new Centre_Manager($_SESSION['login'],$_SESSION['pass']);
            echo "<p>Login details are correct, Welcome ". $manager->getLogin() ." <br>Click here to <a href='logoutOverview.php' title='Logout'> logout </a> .<br> ";
            $_SESSION['logged_in_2'] = true;
            $dbConnection->displayOverview();
        }

        else if(isset($_POST['submit']))      // If the form has been submitted, do this:
        {
            $_SESSION['logged_in_2'] = false;

            $login = $_POST['login'];      // Get the login details and escape them
            $login  = mysqli_escape_string($dbConnection->getLink(),$login);

            $password = $_POST['password'];

            $_SESSION['login'] = $login;
            $_SESSION['pass'] = $password;

            $manager = new Centre_Manager($login,$password);  // create a new manager object and assign variables
            if($dbConnection->verifyManager($manager))        // Check if the login details are correct.
            {                                                 // If they are, echo message and display overview of seminars
                echo "<p>Login details are correct, Welcome ". $manager->getLogin() . " <br>Click here to <a href='logoutOverview.php' title='Logout'> logout </a> .<br> </p>";
                $_SESSION['logged_in_2'] = true;
                $dbConnection->displayOverview();
            }
            else
            {
                echo "Login details are incorrect!";         // If not, echo message and take back to login page
                header("Location:overview.php");
            }


        }
        else {   // If not, display login form


        ?>

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

    </div><!--Here ends the content-->
</body>

<?php


}




?>

</html>