

<!DOCTYPE html>
<html>
<head lang="en">
    <?php
    /**
     * Created by PhpStorm.
     * User: Remus
     * Date: 16/12/2014
     * Time: 17:37
     */
    

    $servername = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbname = "CSY2028_13430492";

    $connection = new mysqli($servername, $username, $password, $dbname);

    // Check connection for errors

    if ($connection->connect_error) {
        die("Connection failed :" . $connection->connect_error);
    }

    $findAvailableRoomsSQL =$connection->query( "SELECT roomId,capacity from Room where available=TRUE");

    $row = $findAvailableRoomsSQL->fetch_assoc();
    $capacity = (int) $row['capacity'];

    $connection->close();


    ?>
<meta charset="UTF-8">
<title>Add page</title>
</head>
<body>
<form action="add.php" method="post">
    <p>Title:<input type="text" name="title" value=""></p><br>

    <p>Location<input type="text" name="location" value=""></p><br>

    <p>Starting Time and Date<input type="text" name="startTime" value=""></p><br>

    <p>Ending Time and Date<input type="text" name="endTime" value=""></p><br>

    <p>Description:<input type="text" name="description" value=""></p><br>

    <p>Room to be Held In:
        <select name="room">
            <?php
            function displayRooms()
            {
                global $findAvailableRoomsSQL;
                while ($row = $findAvailableRoomsSQL->fetch_assoc())
                {
                    echo "<option>" .$row['roomId']. "</i><br></option>";
                }
            }
            displayRooms();

            ?>


        </select></p><br>

    <p>Places Available in Seminar:<input type="number" min="1" max="<?php global $_capacity; echo $capacity?>" name="placesAvailable" value=""></p><br>

    <p><input type="submit" value="Add Seminar"></p>


</form>

</body>
</html>