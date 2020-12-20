<?php

class Alarm {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserAlarms() {
        $sql = "SELECT `noteid`, `text`, `expiry`, `repeat`, `username`, `email` "
                . "FROM `users` JOIN `notes` USING(`userid`) WHERE `alarm` = 'true'";
        $this->db->query($sql);
        $results = $this->db->resultSet();
        return $results;
    }

    public function updateSent($noteid) {
        $this->db->query('UPDATE `notes` SET `lastsent` = now() WHERE `noteid` = :noteid');
        $this->db->bind(':noteid', $noteid);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
