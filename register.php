<!DOCTYPE html>
<html>
<head lang="en">

<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 04/01/2015
 * Time: 11:37
 */
if (isset($_POST['firstName']))
{
    require_once('databaseConnection.php');
    require_once('Attendee.php');

    $host = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbName = "CSY2028_13430492";

    $dbConnection = new databaseConnection($host, $username, $password, $dbName);

    echo  "Post set!";
    //var_dump($_POST);

    $firstName = $_POST['firstName'];
    $firstName  = mysqli_escape_string($dbConnection->getLink(),$firstName);

    $lastName = $_POST['lastName'];
    $lastName = mysqli_escape_string($dbConnection->getLink(),$lastName);

    $email = $_POST['email'];
    $email = mysqli_escape_string($dbConnection->getLink(),$email);

    $institution = $_POST['institution'];
    $institution = mysqli_escape_string($dbConnection->getLink(),$institution);

    $attendeeToAdd = new Attendee($firstName,$lastName,$email,$institution);

    $dbConnection->addAttendee($attendeeToAdd);}

else
{

// This retrieves the seminar that the attendee wants to register for from the url and assigns it into the variable for further use

$seminarId = $_GET['seminarId'];
$seminarId = (int) $seminarId;
//echo "Seminar ID REg " . $seminarId;

    ?>
    <style>
        .regForm
        {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 50px;
            margin-left: 30px;
        }

    </style>




    <meta charset="UTF-8">
    <title>Register Script</title>

</head>

<body>

<form action="register.php?seminarId=<?php echo $seminarId; ?>" method="post">
    <table class="regForm">

        <caption><b>Attendance Registration Form<b></caption>
        <tr><td>First Name :</td><td><input type="text" name="firstName" value=""></td></tr>

        <tr><td>Last Name :</td><td> <input type="text" name="lastName" value=""></td></tr>

        <tr><td>E-mail :</td><td> <input type="text" name="email" value=""></td></tr>

        <tr><td>Institution :</td><td><input type="text" name="institution" value=""></td></tr>

        <tr><td><input type="submit" value="Register"></td></tr>

    </table>


</form>





</body>
<?php

}

?>
</html>