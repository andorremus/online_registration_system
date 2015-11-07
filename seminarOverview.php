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
        }
        td
        {
            border: solid 1px #000000;
        }
        #instNo
        {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 10px;
            margin-left: 30px;
            overflow: visible;
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
    <title>Seminar Overview </title>

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

                // Script created to deal with XMLHttpRequest


            </script>
        </a>


    </div><!--This is the end of the header-->

    <div id="content"><!--Here's the content-->
        <?php

        require_once('databaseConnection.php'); // Import all the classes needed
        require_once('seminarOrganiser.php');
        session_start();
        $host = "194.81.104.22";      // Define all the variables needed and create connection
        $username = "s13430492";
        $password = "remian10";
        $dbName = "CSY2028_13430492";
        $dbConnection = new databaseConnection($host, $username, $password, $dbName);

        if(isset($_SESSION['logged_in']))
        {
            $seminarOrganiser = new seminarOrganiser($_SESSION['loginName'],$_SESSION['password']);
            echo "Login details are correct, Welcome ". $seminarOrganiser->getLoginName() . " . Click here to <a href='logout.php' title='Logout'> logout </a> .<br> ";
            $_SESSION['logged_in'] = true;
            echo "</br> <b> The following seminars are organised by you : </b>";
            $dbConnection->displaySeminar($seminarOrganiser);
            echo " <input type='button' act> ";
        }

        else if(isset($_POST['submit']) )      // If the form has been submitted, do this
        {
            $_SESSION['loginName'] = $_POST['loginName'];
            //echo $_SESSION['loginName'];
            $_SESSION['password']  = $_POST['password'];
            //echo $_SESSION['password'];

            $login_name = $_POST['loginName'];        // Get the login and password from the form and assign into variables
            $login_name  = mysqli_escape_string($dbConnection->getLink(),$login_name);

            $password = $_POST['password'];

            $seminarOrganiser = new seminarOrganiser($login_name,$password);          //  Create a new seminar organiser object and check if the login details are correct,
            if($dbConnection->verifyOrganiser($seminarOrganiser) )                    //   if they are echo message and display the seminars organised by him/her
            {                                                                         //   if not echo a message and take the user back to the login page
                echo "Login details are correct, Welcome ". $seminarOrganiser->getLoginName() . " . Click here to <a href='logout.php' title='Logout'> logout </a> .<br> ";
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

    </div><!--Here ends the content-->
</body>
<?php

}

?>
</html>
