<?php
namespace app\models;

use DateTime;

class Review{
    public int $id;

    public DateTime $publicationTime;

    public string $fullName;

    public string $message;

    public function __construct()
    {
        $this->id = 0;
        $this->publicationTime = new DateTime();
    }
}
?>