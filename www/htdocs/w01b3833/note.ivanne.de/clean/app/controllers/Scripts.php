<?php

/**
 * I DON'T UNSERSTAND YET
 * With this controller I only get the index view.
 * I still have to find out what's wrong.
 * If you see the reason then 
 * please let me know
 * thx.
 */
class Scripts extends Controller {

    public function __construct() {
        
    }

    public function notes() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->noteModel = $this->model('Note');

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $_SESSION['searcht'] = $_POST['searcht'];
            $_SESSION['datemin'] = $_POST['datemin'];
            $_SESSION['datemax'] = $_POST['datemax'];
            $_SESSION['sortcol'] = $_POST['sortcol'];
            $_SESSION['ascdesc'] = $_POST['ascdesc'];

            $noteModel = new Note();
            $notes = $noteModel->findNotesByFilters();

            $data = [
                'notes' => $notes
            ];
            $this->view('scripts/notes', $data);
            
        } else {
            echo 'NO POST';
        }
    }

    public function pix() {
        $data = [
            'title' => 'pix'
        ];
        $this->view('scripts/pix', $data);
    }

    public function size() {
        $data = [
            'title' => 'size'
        ];
        $this->view('scripts/size', $data);
    }

    public function xml() {
        $data = [
            'title' => 'xml'
        ];
        $this->view('scripts/xml', $data);
    }

}
