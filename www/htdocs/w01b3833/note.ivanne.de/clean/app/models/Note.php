<?php

class Note {

    static private $db;

    public function __construct() {
        $this->db = new Database();
    }

    function todoIsDue($repeat, $expiry, $current_date) {
        $expiry_unix_ts = strtotime($expiry);
        $current_unix_ts = strtotime($current_date);
        $expiry_date = date("Y-m-d", $expiry_unix_ts);
        $tomorrow = strtotime($current_date . " +1 day");
        $current_dow = date("l", $current_unix_ts);
        $current_dom = date("d", $current_unix_ts);
        $current_doy = date("z", $current_unix_ts);
        $tomorrow_month = date("m", $tomorrow);
        $current_month = date("m", $current_unix_ts);
        $tomorrow_year = date("Y", $tomorrow);
        $current_year = date("Y", $current_unix_ts);
        $expiry_dow = date("l", $expiry_unix_ts);
        $expiry_dom = date("d", $expiry_unix_ts);
        $expiry_doy = date("z", $expiry_unix_ts);
        $day_diff = floor(abs($current_unix_ts - $expiry_unix_ts) / 86400);
        return (($current_date == $expiry_date) || ((is_numeric($repeat) && $day_diff % $repeat == 0) || ($repeat == 'Täglich') || ($repeat == 'Wöchentlich' && $current_dow == $expiry_dow) || ($repeat == 'Monatlich' && ($current_dom == $expiry_dom || ($expiry_dom > 28 && $tomorrow_month != $current_month))) || ($repeat == 'Jährlich' && ($current_doy == $expiry_doy || ($expiry_doy == 365 && $tomorrow_year != $current_year)))));
    }

    public function findNotesByFilters() {
        $sql = "SELECT `noteid`, `text`, group_concat(`pixid` separator ' ') as `pixids`, "
                . "`expiry`, `repeat`, `alarm` FROM `notes` LEFT JOIN `pictures` "
                . "USING(`noteid`) WHERE `userid` = " . $_SESSION['userid'];
        if ($_SESSION['searcht'] != "") {
            $sql .= " AND `text` LIKE '%" . $_SESSION['searcht'] . "%' ";
        }
        $sql .= " AND ((DATE(`expiry`) BETWEEN '" . $_SESSION['datemin'] . "' AND '" . $_SESSION['datemax'] . "') OR `repeat` != 'Einmalig') "
                . "GROUP BY `noteid` ORDER BY `" . $_SESSION['sortcol'] . "` " . $_SESSION['ascdesc'];
        //echo $sql;
        $this->db->query($sql);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getAllNotes() {
        $sql = "SELECT * FROM `notes`  WHERE `userid` = " . $_SESSION['userid'];
        $this->db->query($sql);
        $results = $this->db->resultSet();
        return $results;
    }

    public function findPictureById($pixid) {
        $sql = "SELECT `pixtype`, `pixdata` FROM `pictures` WHERE `pixid`= :pixid";
        $this->db->query($sql);
        $this->db->bind(':pixid', $pixid * 1);
        $row = $this->db->single();
        return $row;
    }

    public function addNote($data) {
        $inserted = false;
        $sql = "INSERT INTO `notes`(`userid`, `text`, `expiry`, `repeat`, `alarm`)"
                . " VALUES(:userid, :text, :expiry, :repeat, :alarm)";
        $this->db->queryReturningLastId($sql);
        $this->db->bind(':userid', $_SESSION['userid'] * 1);
        $this->db->bind(':text', $data['text']);
        $this->db->bind(':expiry', $data['expiry']);
        $this->db->bind(':repeat', $data['repeat']);
        $this->db->bind(':alarm', $data['alarm']);
        if ($this->db->execute()) {
            $lastid = $this->db->getHandler()->lastInsertId();
            if ($lastid > 0) {
                if (!empty($_FILES["pictures"]["type"])) {
                    if ($this->addPix($lastid)) {
                        $inserted = true;
                    }
                } else {
                    $inserted = true;
                }
            }
        }
        return $inserted;
    }

    private function addPix($noteid) {
        $inserted = false;
        $type = $_FILES["pictures"]["type"];
        foreach ($_FILES["pictures"]["error"] as $f => $error) {
            if ($error == 0) {
                $size_ok = $_FILES["pictures"]["size"][$f] < 5 * MB;
                if (getimagesize($_FILES["pictures"]["tmp_name"][$f]) && $size_ok) {
                    $name = basename($_FILES["pictures"]["name"][$f]);
                    $type = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($type, array("jpg", "png", "jpeg", "gif"))) {
                        $data = file_get_contents($_FILES["pictures"]["tmp_name"][$f]);
                        $sql = "INSERT INTO `pictures`(`noteid`, `pixname`, `pixtype`, `pixdata`)"
                                . " VALUES($noteid, '$name', '$type', 0x" . bin2hex($data) . ")";
                        $this->db->query($sql);
                        if ($this->db->execute()) {
                            $inserted = true;
                        }
                    }
                }
            }
        }
        return $inserted;
    }

    public function removeNote($noteid) {
        $deleted = false;
        $sql = "DELETE FROM `pictures` WHERE `noteid` = :noteid";
        $this->db->query($sql);
        $this->db->bind(':noteid', $noteid);
        if ($this->db->execute()) {
            $sql = "DELETE FROM `notes` WHERE `noteid` = :noteid";
            $this->db->query($sql);
            $this->db->bind(':noteid', $noteid);
            if ($this->db->execute()) {
                $deleted = true;
            }
        }
        return $deleted;
    }

    public function changeNote($data) {
        $updated = false;
        $sql = "UPDATE `notes` SET `text` = :newtext, `expiry` = :newexpiry, "
                . "`repeat` = :newrepeat, `alarm` = :newalarm "
                . " WHERE `noteid` = :noteid";
        $this->db->queryReturningLastId($sql);
        $this->db->bind(':newtext', $data['newtext']);
        $this->db->bind(':newexpiry', $data['newexpiry']);
        $this->db->bind(':newrepeat', $data['newrepeat']);
        $this->db->bind(':newalarm', $data['newalarm']);
        $this->db->bind(':noteid', $data['noteid'] * 1);
        if ($this->db->execute()) {
            $updated = true;
        }
        return $updated;
    }

    public function storeFromXML() {
        $stored = false;
        if ($_FILES['upload']['type'] == 'text/xml') {
            if ($_FILES['upload']['size'] <= 5 * MB) {
                $filename = 'user' . $_SESSION['userid'] . '_' . time()
                        . '_' . basename($_FILES['upload']['name']);
                //if (move_uploaded_file($_FILES['upload']['tmp_name'], 'xml/' . $filename)) {
                    //$list = simplexml_load_file('xml/' . $filename);
                    $list = simplexml_load_file($_FILES['upload']['tmp_name']);
                    foreach ($list as $note) {
                        $sql = "INSERT INTO `notes`(`userid`, `text`, `expiry`, `repeat`, `alarm`) "
                                . "VALUES(:userid, :xmltext, :xmlexpiry, :xmlrepeat, :xmlalarm)";
                        $this->db->query($sql);
                        $this->db->bind(':userid', $_SESSION['userid'] * 1);
                        $this->db->bind(':xmltext', $note->text);
                        $this->db->bind(':xmlexpiry', $note->expiry);
                        $this->db->bind(':xmlrepeat', $note->repeat);
                        $this->db->bind(':xmlalarm', $note->alarm);
                        if ($this->db->execute()) {
                            $stored = true;
                        }
                    }
                    //if (!unlink('xml/' . $filename)) {$stored = false;}
                //}
            }
        }
        return $stored;
    }

}
