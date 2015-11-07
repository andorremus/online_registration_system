<!DOCTYPE html>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<html>
<head>
    <script type="text/javascript" src="javascript.js"></script>
    <link type="text/css" rel="stylesheet" href="stylesheet.css">

    <style type="text/css"> /* CSS for the Page Design */
        .regForm
        {
            table-border-color-light: #1f1b82;
            border-style: solid;
            margin-top: 50px;
            margin-left: 30px;
        }

        #content
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

</head>

<title>Add Page</title>

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

require_once ('Seminar.php');                  // Import the required classes
require_once ('databaseConnection.php');
require_once('Speaker.php');

if (isset($_POST))
{
    $dbConnection = new databaseConnection('194.81.104.22','s13430492','remian10','CSY2028_13430492'); // Create database connection

    $title = $_POST['title'];                                                    // Get the post values, assign them to variables and escape them
    $title  = mysqli_escape_string($dbConnection->getLink(),$title);

    $startTime = $_POST['startTime'];
    $startTime = mysqli_escape_string($dbConnection->getLink(),$startTime);

    $endTime = $_POST['endTime'];
    $endTime = mysqli_escape_string($dbConnection->getLink(),$endTime);

    $description = $_POST['description'];
    $description = mysqli_escape_string($dbConnection->getLink(),$description);

    $placesAvailable = $_POST['capacity'];
    $placesAvailable = (int)$placesAvailable;

    $room = $_POST['room'];
    $room = (int) $room;

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


        $seminarToAdd = new Seminar($title, $startTime, $endTime, $description, $placesAvailable, $room);  // Create a seminar object and initialise it with needed values
        $speakerToAdd = new Speaker($firstName, $lastName, $email, $address, $institution);               // Create a speaker object and initialise it with needed values
        //var_dump($seminarToAdd);


        // Check if the selected room is not busy at the selected time

        $sqlCheckAvailability = "SELECT * from Seminar INNER JOIN Allocated_Room USING (seminarId) where '$startTime' <= endTime AND '$endTime' >= startTime and Allocated_Room.roomId=$room";
        $roomAvailableResult = $dbConnection->getLink()->query($sqlCheckAvailability);
        $roomAvailability = $roomAvailableResult->fetch_assoc();

        if (is_null($roomAvailability))
        {                      // If the room is available ...
            $dbConnection->addSeminar($seminarToAdd, $speakerToAdd);                             // Add seminar and speaker to the database using the open connection

            $sqlFindSeminar = "SELECT max(seminarId) AS semId FROM Seminar";     // Get the seminar that was just created and use it for displaying the registration link
            //echo $sqlFindSeminar;
            $seminarAdded = $dbConnection->getLink()->query($sqlFindSeminar);
            //echo $seminarAdded;
            $seminarArray = $seminarAdded->fetch_assoc();

            echo "This is the link with which people can register:";
            echo "<br> http://www.computing.northampton.ac.uk/~13430492/CSY2028/assign1/register.php?seminarId=" . $seminarArray['semId'];
        } else {
            echo " The room is not available at that specific time! ";
            header("Location:index.php?unavailable=true");    // Return to the index page with variable set in URL
        }










}
?>




    </div><!--Here ends the content-->
</body>
</html>