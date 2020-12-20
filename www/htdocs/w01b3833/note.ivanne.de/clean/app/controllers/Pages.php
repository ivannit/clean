<?php

class Pages extends Controller {

    public function __construct() {}

    public function info() {
        $data = [
            'title' => 'Info'
        ];
        $this->view('pages/info', $data);
    }

    public function impressum() {
        $data = [
            'title' => 'Impressum'
        ];
        $this->view('pages/impressum', $data);
    }

    public function datenschutz() {
        $data = [
            'title' => 'Datenschutz'
        ];
        $this->view('pages/datenschutz', $data);
    }
    
    public function quotes() {
        $data = [
            'title' => 'Zitate'
        ];
        $this->view('pages/quotes', $data);
    }

}
