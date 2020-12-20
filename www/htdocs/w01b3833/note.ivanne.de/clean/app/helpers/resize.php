<?php

function resizePicture($pixdata, $pixtype, $divisor) {
    if (isset($_SESSION['screenwidth']) && $_SESSION['screenwidth'] > 0) {
        $screenwidth = $_SESSION['screenwidth'];
        $im = imagecreatefromstring($pixdata);
        $x = imagesx($im);
        $y = imagesy($im);
        $ratio = $y / $x;
        $x_new = floor($screenwidth / $divisor);
        $y_new = floor($x_new * $ratio);
        $im_new = imagecreatetruecolor($x_new, $y_new);
        imagecopyresampled($im_new, $im, 0, 0, 0, 0, $x_new, $y_new, $x, $y);
        switch ($pixtype) {
            case 'png':
                imagepng($im_new);
                break;
            case 'gif':
                imagegif($im_new);
                break;
            default:
                imagejpeg($im_new);
        }
        imagedestroy($im);
    } else {
        echo $pixdata;
    }
}
