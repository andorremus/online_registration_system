

<!DOCTYPE html>
<html>
<head lang="en">
    <?php
    /**
     * Created by PhpStorm.
     * User: Remus
     * Date: 06/01/2015
     * Time: 17:37 Version 1.0.5 Functionality is correct
     */

    require_once('databaseConnection.php');

    $host = "194.81.104.22";
    $username = "s13430492";
    $password = "remian10";
    $dbName = "CSY2028_13430492";

    $dbConnection = new databaseConnection($host, $username, $password, $dbName);

    $sqlRoomCapacities ="SELECT capacity from Room";
    $roomsCapacities = $dbConnection->getLink()->query($sqlRoomCapacities);

    $sqlRoomNo = "SELECT roomId FROM Room";
    $roomNo = $dbConnection->getLink()->query($sqlRoomNo);

    $sqlRoomDetails = "SELECT roomName,location from Room";
    $roomDetails = $dbConnection->getLink()->query($sqlRoomDetails);

    $roomDetailsArray = array();
    $x = 0;
    while($row = $roomDetails->fetch_assoc())
    {
        $roomDetailsArray[$x] = $row['roomName'];
        $x++;
        $roomDetailsArray[$x] = $row['location'];
        $x++;
    }
    //var_dump($roomDetailsArray[0]);



    $rCap = array();
    $i = 0;
    while ($row = $roomsCapacities->fetch_assoc())
    {
        $rCap[$i] =  $row['capacity'];
        $rCap[$i] = (int) $rCap[$i];
        $i++;
    }


    function displayRooms()
    {
        global $roomNo;
        while ($row = $roomNo->fetch_assoc())
        {
            echo "<option>" . $row['roomId'] .  "</i><br></option>";
        }
    }







    ?>
    <script>
        function chooseCapacity()
        {
            var roomChosen = document.getElementById("room").value;
            var roomCounter = '<?php echo count($rCap);
                                    $rCap = implode(",",$rCap);?>' ;
            //document.write(roomCounter);
            var capacity = '<?php global $rCap ; echo $rCap ?>';
            capacity =  capacity.split(",");

            var roomDetails = '<?php $roomDetailsArray = implode(",",$roomDetailsArray);
                                     echo $roomDetailsArray;
                                          ?>';
            roomDetails = roomDetails.split(",");
            roomDetails.unshift(null);
            document.getElementById("capacity").max = capacity[roomChosen-1];
            document.getElementById("roomHeld").innerHTML = "Room to be Held In: " + roomDetails[(roomChosen*2)-1] + " - " + roomDetails[(roomChosen*2)];



        }









    </script>



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

        <tr><td id="roomHeld">Room to be Held In:

                </td><td><select id="room" onchange="chooseCapacity()">
                    <?php

                    displayRooms();

                    ?>
                    </select></td></tr>

        <tr><td>Places Available in Seminar:</td><td> <input type="number" min="1" max="" id="capacity" value=""></td></tr>
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