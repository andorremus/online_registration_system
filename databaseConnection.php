<?php
require_once('Seminar.php');

class databaseConnection
{
    private $link;

    public function __construct($host,$user,$pass,$database)
    {
        $this->link = new mysqli($host,$user,$pass,$database);

        if($this->link->connect_errno)
        {
            die('Unable to connect to database: ' . $this->link->connect_error );
        }
    }

    public function addSeminar(Seminar $seminar)
    {
        $title = $seminar->getTitle();
        $startTime = $seminar->getStartTime();
        $endTime = $seminar->getEndTime();
        $description = $seminar->getDescription();
        $placesAvailable = $seminar->getPlacesAvailable();

        $dbConnection = new databaseConnection('194.81.104.22','s13430492','remian10','CSY2028_13430492');
        $sql = "INSERT INTO Seminar VALUES (NULL,$title,$startTime,$endTime,$description,$placesAvailable)";

        if($dbConnection->link->query($sql))
        {
            die('There was an error running the query ' . $dbConnection->link->error);
        }
        else
        {
            echo "New seminar created successfully;";
        }

        $dbConnection->link->close();


    }

}