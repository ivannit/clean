<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $_SESSION['screenwidth'] = $_POST['screenwidth'];
}