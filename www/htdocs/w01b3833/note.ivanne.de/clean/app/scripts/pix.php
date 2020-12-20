<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once APPROOT . '/helpers/start.php';
    require_once APPROOT . '/helpers/resize.php';
    require_once APPROOT . '/models/Note.php';
    
    if (isLoggedIn()) {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

        $pixid = $_GET['id'];
        $noteModel = new Note();
        $pixrow = $noteModel->findPictureById($pixid);

        header('Content-type: image/' . $pixrow->pixtype);

        resizePicture($pixrow->pixdata, $pixrow->pixtype, 1.2);
    }
    
}
