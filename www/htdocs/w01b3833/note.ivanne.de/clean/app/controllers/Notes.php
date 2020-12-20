<?php

class Notes extends Controller {

    public function __construct() {
        if (isLoggedIn()) {
            $this->noteModel = $this->model('Note');
            $this->createNoteSession();
        } else {
            header('location:' . URLROOT . '/pages/index');
        }
    }

    public function createNoteSession() {
        if (!isset($_SESSION['searcht'])) {
            $_SESSION['searcht'] = '';
            $_SESSION['datemin'] = date("Y-m-d");
            $_SESSION['datemax'] = date("Y-m-d", strtotime("+1 day"));
            $_SESSION['sortcol'] = 'expiry';
            $_SESSION['ascdesc'] = 'asc';
        }
    }

    public function list() {
        $notes = $this->noteModel->findNotesByFilters();
        $data = [
            'title' => 'Note',
            'notes' => $notes
        ];
        $this->view('notes/list', $data);
    }

    public function minpix($id = 0) {
        if ($id > 0) {
            $pixrow = $this->noteModel->findPictureById($id);
            $data = [
                'pixdata' => $pixrow->pixdata,
                'pixtype' => $pixrow->pixtype
            ];
            $this->view('notes/minpix', $data);
        } else {
            die('Unerwarteter Fehler');
        }
    }

    public function maxpix($id = 0) {
        if ($id > 0) {
            $pixrow = $this->noteModel->findPictureById($id);
            $data = [
                'pixdata' => $pixrow->pixdata,
                'pixtype' => $pixrow->pixtype
            ];
            $this->view('notes/maxpix', $data);
        } else {
            die('Unerwarteter Fehler');
        }
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'text' => $_POST['text'],
                'picture' => $_POST['picture'],
                'expiry' => $_POST['expiry'],
                'repeat' => $_POST['repeat'],
                'alarm' => $_POST['alarm']
            ];
            if (!$this->noteModel->addNote($data)) {
                die('Unerwarteter Fehler');
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'newtext' => $_POST['newtext'],
                'newexpiry' => $_POST['newexpiry'],
                'newrepeat' => $_POST['newrepeat'],
                'newalarm' => $_POST['newalarm'],
                'noteid' => $_POST['noteid']
            ];
            if (!$this->noteModel->changeNote($data)) {
                die('Unerwarteter Fehler');
            }
        }
    }

    public function delete($id = 0) {
        if ($this->noteModel->removeNote($id)) {
            header('location:' . URLROOT . '/notes/list');
        } else {
            die('Unerwarteter Fehler');
        }
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if ($this->noteModel->storeFromXML()) {
                header('location:' . URLROOT . '/notes/list');
            } else {
                header('location:' . URLROOT . '/notes/list');
            }
        }
    }

}
