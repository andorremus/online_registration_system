
<?php

class Seminar
{

    private $link;
    private $title;
    private $startTime;
    private $endTime;
    private $description;
    private $placesAvailable;
    private $roomId;

    public function __construct($title, $startTime, $endTime, $description, $placesAvailable,$roomId)
    {
        $this->title = $title;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->placesAvailable = $placesAvailable;
        $this->roomId = $roomId;
    }

    public function getTitle() {
        return $this->title;
    }
    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPlacesAvailable()
    {
        return $this->placesAvailable;
    }

    public function getRoomId()
    {
        return $this->roomId;
    }
    public function getLink()
    {
        return $this->link;
    }

}

?>



