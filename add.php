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

if (isset($_POST))
{
    $dbConnection = new databaseConnection('194.81.104.22','s13430492','remian10','CSY2028_13430492');

    $title = $_POST['title'];
    $startTime = $_POST['startTime'];
    $endtime = $_POST['endTime'];
    $description = $_POST['description'];
    $description = mysqli_escape_string($dbConnection->getLink(),$description);
    $room = $_POST['room'];





    //$seminarToAdd = new Seminar()


}
?>





</body>
</html>