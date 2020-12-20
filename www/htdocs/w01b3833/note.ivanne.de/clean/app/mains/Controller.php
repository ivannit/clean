<?php

class Controller {
    
    public $view;

    public function model($model) {
        require_once APPROOT . '/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        $this->view = explode('/', $view)[1];
        if (file_exists(APPROOT . '/views/' . $view . '.php')) {
            require_once APPROOT . '/views/' . $view . '.php';
        } else {
            die('Keine Ansicht');
        }
    }
    
    public function index() {
        $data = [
            'title' => 'Home'
        ];
        $this->view('pages/index', $data);
    }

}
