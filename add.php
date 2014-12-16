<!DOCTYPE html>
<html>
<head>

</head>
<h1>This is the ADD page</h1>

<body>
<?php

$servername = "194.81.104.22";
$username = "s13430492";
$password = "remian10";
$dbname = "CSY2028_13430492";
$placesAvailable = (int)($_POST['placesAvailable']);
//var_dump($placesAvailable);
$roomId = (int)($_POST['roomId']);

//var_dump($roomId);


var_dump($roomId);

// Create a new connection to the database

$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection for errors

if ($connection->connect_error) {
    die("Connection failed :" . $connection->connect_error);
}


// Testing query
/*$registerSQL = "INSERT INTO Seminar (seminarId,title,location,startTime,endTime,description,roomUsed,placesAvailable)
           values (2,'Employable imporvements','Newton Building','2014-01-12 14:00:00','2014-01-12 14:30:00',
               'An event that will make you think about your employability skills',2,250)";*/




/*$lastSeminarId= $connection->query("SELECT MAX('seminarId')from Seminar ");
var_dump($lastSeminarId);*/


// Define the registration query

$registerSQL = "INSERT INTO Seminar (title,startTime,endTime,description,placesAvailable)
                          values ('" . $_POST['title']."','" . $_POST['startTime']."','" . $_POST['endTime']."',
                          '" . $_POST['description']."','$placesAvailable')";
//$allocateRoom ="INSERT INTO Allocated_Room (roomId) values($roomId)";

/* echo $_POST['title'] ;
 echo $_POST['location'];
 echo $_POST['startTime'];
 echo $_POST['endTIme'];
 echo $_POST['description'];
 echo $roomId;
 echo $placesAvailable;*/
//This is to test if it works
// Check if the query is valid

if (!$connection->query($registerSQL )) {
    die('There was an error running the query ' . $connection->error);
} else {
    echo "New seminar created successfully;";
}
$connection->close();







?>





</body>
</html>