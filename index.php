

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

    $findAvailableRoomsSQL ="SELECT roomId,roomName,capacity,location from Room";
    //var_dump($findAvailableRoomsSQL);

    $roomsAvailable = $dbConnection->getLink()->query($findAvailableRoomsSQL);


   /* $row = $roomsAvailable->fetch_assoc();
    $capacity = (int) $row['capacity'];
    var_dump($capacity);
    $name = $row['roomName'];
    var_dump($name);*/


    function displayRooms()
    {
        global $roomsAvailable;
        while ($row = $roomsAvailable->fetch_assoc())
        {
            echo "<option>" . $row['roomId'] .  "</i><br></option>";
        }
    }

    $dbConnection->getLink()->close();





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
<title>Index Page</title>
</head>
<body>
<form action="add.php" method="post">
    <table class="regForm">
        <caption><b>Seminar Registration Form<b></caption>
        <tr><td>Title:</td><td><input type="text" name="title" value=""></td></tr>

        <tr><td>Starting Time and Date (YYYY-MM-DD HH-MM-SS):</td><td> <input type="text" name="startTime" value=""></td></tr>

        <tr><td>Ending Time and Date (YYYY-MM-DD HH-MM-SS) :</td><td> <input type="text" name="endTime" value=""></td></tr>

        <tr><td>Description:</td><td><input type="text" name="description" value=""></td></tr>

        <tr><td>Room to be Held In:

                </td><td><select name="room">
                    <?php
                    displayRooms();
                    ?>
                    </select></td></tr>

        <tr><td>Places Available in Seminar:</td><td> <input type="number" min="1" max="<?php global $capacity; echo $capacity?>" name="placesAvailable" value=""></td></tr>
    </table>

    <table class="regForm">
        <caption><b>Seminar Speakers Registration Form<b></caption>
        <tr><td>First Name:</td><td><input type="text" name="firstName"></td></tr>

        <tr><td>Last Name:</td><td><input type="text" name="lastName"></td></tr>

        <tr><td>E-mail:</td><td><input type="text" name="email"></td></tr>

        <tr><td>Address:</td><td><input type="text" name="address"></td></tr>

        <tr><td>Institution:</td><td><input type="text" name="institution"></td></tr>





        <tr><td><input type="submit" value="Add Seminar"></td></tr>
    </table>


</form>

</body>
</html>