<?php 

    header('Content-Type: image/jpeg');
    header('X-APP-HOSTNAME: ' . gethostname());
    header('X-APP-TIME: ' . date('d.m.Y H:i:s'));

    $img   = ImageCreateFromJPEG('cat.jpg');
    $color = imagecolorallocate($img, 0, 0, 0);

    imagestring($img, 5, 146, 22, 'Host: ' . gethostname(), $color);
    imagestring($img, 5, 146, 36, date('d.m.Y H:i:s'), $color);

    imagejpeg($img);
