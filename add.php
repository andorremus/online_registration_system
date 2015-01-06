<!DOCTYPE html>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<html>
<head>

</head>
<h1>This is the ADD page</h1>

<body>
<?php

require_once ('Seminar.php');
require_once ('databaseConnection.php');
require_once('Speaker.php');

if (isset($_POST))
{
    $dbConnection = new databaseConnection('194.81.104.22','s13430492','remian10','CSY2028_13430492');

    $title = $_POST['title'];
    $title  = mysqli_escape_string($dbConnection->getLink(),$title);

    $startTime = $_POST['startTime'];
    $startTime = mysqli_escape_string($dbConnection->getLink(),$startTime);

    $endTime = $_POST['endTime'];
    $endTime = mysqli_escape_string($dbConnection->getLink(),$endTime);

    $description = $_POST['description'];
    $description = mysqli_escape_string($dbConnection->getLink(),$description);

    $placesAvailable = $_POST['placesAvailable'];
    $placesAvailable = (int)$placesAvailable;

    $room = $_POST['room'];
    $room = (int) $room;

    //var_dump($room);

   /* echo $room;
    echo $title;
    echo $description;
    echo $endTime;
    echo $startTime;
    echo $placesAvailable;*/

    $firstName = $_POST['firstName'];
    $firstName = mysqli_escape_string($dbConnection->getLink(),$firstName);

    $lastName = $_POST['lastName'];
    $lastName = mysqli_escape_string($dbConnection->getLink(),$lastName);

    $email = $_POST['email'];
    $email = mysqli_escape_string($dbConnection->getLink(),$email);

    $address = $_POST['address'];
    $address = mysqli_escape_string($dbConnection->getLink(),$address);

    $institution = $_POST['institution'];
    $institution = mysqli_escape_string($dbConnection->getLink(),$institution);

    $seminarToAdd = new Seminar($title,$startTime,$endTime,$description,$placesAvailable,$room);
    $speakerToAdd = new Speaker($firstName,$lastName,$email,$address,$institution);
    //var_dump($seminarToAdd);
    $dbConnection->addSeminar($seminarToAdd,$speakerToAdd);








}
?>





</body>
</html>