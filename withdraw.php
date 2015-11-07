<!DOCTYPE html>
<html>
<head lang="en">
    <script type="text/javascript" src="javascript.js"></script>
    <link type="text/css" rel="stylesheet" href="stylesheet.css">





    <style>
        .regForm {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 50px;
            margin-left: 30px;
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
    </head>

    <title>Unregister Page</title>

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
         * Date: 08/01/2015
         * Time: 11:21
         */
        if(isset($_POST['submit']))
        {
            require_once('databaseConnection.php');    // Import all the needed classes
            require_once('Attendee.php');

            $host = "194.81.104.22";          // Define the variables needed to establish the connection and create it
            $username = "s13430492";
            $password = "remian10";
            $dbName = "CSY2028_13430492";
            $dbConnection = new databaseConnection($host, $username, $password, $dbName);

            $firstName = $_GET['firstName'];                                             // Get all the values from the url, assign them to variables and escape them
            $firstName  = mysqli_escape_string($dbConnection->getLink(),$firstName);

            $lastName = $_GET['lastName'];
            $lastName = mysqli_escape_string($dbConnection->getLink(),$lastName);

            $email = $_GET['email'];
            $email = mysqli_escape_string($dbConnection->getLink(),$email);

            $institution = $_GET['institution'];
            $institution = mysqli_escape_string($dbConnection->getLink(),$institution);

            $seminarId = $_GET['seminarId'];
            $seminarId =  (int) $seminarId;

            $attendeeToWithdraw = new Attendee($firstName, $lastName, $email, $institution);  // Create a attendee object with the previous values



            $dbConnection->withdrawAttendee($attendeeToWithdraw,$seminarId);                 // Invoke the withdrawAttendee method of the database connection
            //  to withdraw the attendee

        }
        else
        {
        $firstName = $_GET['firstName'];
        $lastName = $_GET['lastName'];
        $email = $_GET['email'];
        $institution = $_GET['institution'];
        $seminarId = $_GET['seminarId'];

        ?>

    <form action="" method="post">
        <table class="regForm">

            <caption><b>Attendance Withdrawal Form<b></caption>
            <tr>
                <td>First Name :</td>
                <td><input type="text" name="firstName" value="<?php echo $firstName ?>" readonly></td>
            </tr>

            <tr>
                <td>Last Name :</td>
                <td><input type="text" name="lastName" value="<?php echo $lastName ?>" readonly></td>
            </tr>

            <tr>
                <td>E-mail :</td>
                <td><input type="text" name="email" value="<?php echo $email ?>"readonly></td>
            </tr>

            <tr>
                <td>Institution :</td>
                <td><input type="text" name="institution" value="<?php echo $institution ?>" readonly></td>
            </tr>

            <tr>
                <td>Seminar :</td>
                <td><input type="text" name="seminarId" value="<?php echo $seminarId ?>" readonly></td>
            </tr>

            <tr>
                <td><input type="submit" value="Unregister" name="submit"></td>
            </tr>

        </table>


    </form>


    </div><!--Here ends the content-->
</body>


<?php
}
?>
</html>
