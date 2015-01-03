

<!DOCTYPE html>
<html>
<head lang="en">
    <?php
    /**
     * Created by PhpStorm.
     * User: Remus
     * Date: 16/12/2014
     * Time: 17:37 222
     */

    require_once('databaseConnection.php');

    $host = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbName = "CSY2028_13430492";

    $dbConnection = new databaseConnection($host, $username, $password, $dbName);

    $findAvailableRoomsSQL ="SELECT roomId,capacity from Room";

    $roomsAvailable = $dbConnection->getLink()->query($findAvailableRoomsSQL);
    var_dump($roomsAvailable);

    $row = $roomsAvailable->fetch_assoc();
    $capacity = (int) $row['capacity'];
    var_dump($capacity);

    function displayRooms()
    {
        global $roomsAvailable;
        while ($row = $roomsAvailable->fetch_assoc())
        {
            echo "<option>" .$row['roomId'].$row['roomName']. "</i><br></option>";
        }
    }

    $dbConnection->getLink()->close();





    ?>
<meta charset="UTF-8">
<title>Add page</title>
</head>
<body>
<form action="add.php" method="post">
    <p>Title:<input type="text" name="title" value=""></p><br>

    <p>Location:<input type="text" name="location" value=""></p><br>

    <p>Starting Time and Date (YYYY-MM-DD HH-MM-SS): <input type="text" name="startTime" value=""></p><br>

    <p>Ending Time and Date (YYYY-MM-DD HH-MM-SS) :<input type="text" name="endTime" value=""></p><br>

    <p>Description:<input type="text" name="description" value=""></p><br>

    <p>Room to be Held In:
        <select name="room">
            <?php

            displayRooms();

            ?>


        </select></p><br>

    <p>Places Available in Seminar:<input type="number" min="1" max="<?php global $_capacity; echo $capacity?>" name="placesAvailable" value=""></p><br>

    <p><input type="submit" value="Add Seminar"></p>


</form>

</body>
</html>