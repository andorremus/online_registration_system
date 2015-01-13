<!DOCTYPE html>
<html>
<head lang="en">


<?php
/**
 * Created by PhpStorm.
 * User: Remus
 * Date: 08/01/2015
 * Time: 11:21
 */
if(isset($_POST['submit']))
{
    require_once('databaseConnection.php');
    require_once('Attendee.php');

    $host = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbName = "CSY2028_13430492";
    $dbConnection = new databaseConnection($host, $username, $password, $dbName);

    $firstName = $_GET['firstName'];
    $firstName  = mysqli_escape_string($dbConnection->getLink(),$firstName);

    $lastName = $_GET['lastName'];
    $lastName = mysqli_escape_string($dbConnection->getLink(),$lastName);

    $email = $_GET['email'];
    $email = mysqli_escape_string($dbConnection->getLink(),$email);

    $institution = $_GET['institution'];
    $institution = mysqli_escape_string($dbConnection->getLink(),$institution);

    $seminarId = $_GET['seminarId'];
    $seminarId =  (int) $seminarId;

    $attendeeToWithdraw = new Attendee($firstName, $lastName, $email, $institution);



    $dbConnection->withdrawAttendee($attendeeToWithdraw,$seminarId);


}
else
{
$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$email = $_GET['email'];
$institution = $_GET['institution'];
$seminarId = $_GET['seminarId'];

?>

    <style>
        .regForm {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 50px;
            margin-left: 30px;
        }

    </style>


    <meta charset="UTF-8">
    <title>Unregister Script</title>

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

</head>

<body>

</body>


<?php
}
?>
</html>
