<!DOCTYPE html>
<html>
<head lang="en">
    <style>

        *
        {
            font-family: "Book Antiqua", "Times New Roman";
        }
        #tableTicket
        {
            border-collapse: collapse;
            font-family: sans-serif;
            border-color: 000000;
            width: 50%;

        }
        td
        {
            border: solid 1px black;
            text-align: center;
        }
        #seminarNo
        {
            font-size: 20px;
            text-align: center;
        }

        #names
        {

        }

        #ticketId
        {
            font-size: 20px;
            text-align: center;
        }

        #title
        {

        }

        #description
        {
            height: auto;
            width: auto;
        }

        #locationAndTime
        {

        }
    </style>


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
        //require_once('./PHPMailer/PHPMailerAutoload.php');

        $host = "194.81.104.22";
        $username = "s13430492";
        $password = "remian10";
        $dbName = "CSY2028_13430492";
        //$mail = new PHPMailer;
        $seminarId = $_GET['seminarId'];

        // Create a database connection object for further use
        $dbConnection = new databaseConnection($host, $username, $password, $dbName);

        //echo  "Post set!";
        //var_dump($_POST);

        // Get $_POST values from the form

        $firstName = $_POST['firstName'];
        $firstName  = mysqli_escape_string($dbConnection->getLink(),$firstName);

        $lastName = $_POST['lastName'];
        $lastName = mysqli_escape_string($dbConnection->getLink(),$lastName);

        $email = $_POST['email'];
        $email = mysqli_escape_string($dbConnection->getLink(),$email);

        $institution = $_POST['institution'];
        $institution = mysqli_escape_string($dbConnection->getLink(),$institution);

        // Retrieve seminar places availability
        $sqlCheckPlaces = "SELECT DISTINCT COUNT(DISTINCT Attendee_attends_Seminar.attendeeId) as placesFilled,Seminar.placesAvailable from Attendee_attends_Seminar JOIN Seminar USING  (seminarId) WHERE Attendee_attends_Seminar.seminarId =$seminarId";
        $sqlPlacesResult = $dbConnection->getLink()->query($sqlCheckPlaces);
        $row = $sqlPlacesResult->fetch_assoc();
        $placesFilled = $row['placesFilled'];
        $placesAvailable = $row['placesAvailable'];
        $placesFilled= (int) $placesFilled;
        $placesAvailable= (int) $placesAvailable;

        // Check wheter there are available places in the seminar and branch out accordingly

        if($placesFilled < $placesAvailable)
        {
            $attendeeToAdd = new Attendee($firstName, $lastName, $email, $institution);
            $dbConnection->addAttendee($attendeeToAdd);

        }
        else
        {
            echo "<h2> We are sorry but there are no more available spaces in the seminar. ";
        }

        $dbConnection->getLink()->close();

        /*$mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmailcom';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "andor.remus@gmail.com";
        $mail->Password = "passhere";
        $mail->setFrom('andor.remus@gmail.com','Remus Andor');
        $mail->addReplyTo('andor.remus@gmail.com','Remus Andor');
        $mail->addAddress($email,$firstName ." ". $lastName);
        $mail->Subject = 'Ticket for the Registration';
        $mail->Body = "This is ticket body";
        $mail->AltBody = 'This is a plain text message body';
        if(!$mail->send())
        {
            echo "Mailer Error :" . $mail->ErrorInfo;
        }
        else
        {
            echo "Message sent!";
        }*/


    }

    else
    {

    // This retrieves the seminar that the attendee wants to register for from the url and assigns it into the variable for further use

    $seminarId = $_GET['seminarId'];
    $seminarId = (int) $seminarId;

    if(empty($seminarId))
    {
        echo " There is no seminar inputted for registration!";
    }
    else
    {
        echo  "<h2><b>" ." You are registering for seminar number : ". $seminarId  . "</b></h2>";
    }
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