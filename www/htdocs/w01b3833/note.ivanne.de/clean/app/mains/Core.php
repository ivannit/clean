<?php

/**
 * URL FORMAT: /controller/method/params
 */
class Core {

    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // exclude scripts from URL handling especially for AJAX calls
        if ($url[0] == 'scripts') {
            require_once APPROOT . '/' . $url[0] . '/' . $url[1];
        } else {

            // capitalize first letter
            if (file_exists(APPROOT . '/controllers/' . ucwords($url[0]) . '.php')) {
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            }

            require_once APPROOT . '/controllers/' . $this->currentController . '.php';

            // instantiate controller class
            $this->currentController = new $this->currentController;

            // check for second part of url
            if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }

            $this->params = $url ? array_values($url) : [];

            // call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }

}
