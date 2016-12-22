<?php
    require_once('./library/ImageClass.php');
    $image_object = new ImageClass;
    $resample_res = $image_object->resampleImage('./source/jietou01.jpg', './destination/jietou01-thumnail.jpg', 50, 480, 0, 1);
    echo '<pre>';
    print_r(getimagesize('./destination/jietou01.jpg'));
    echo '</pre>';
    echo '<pre>';
    print_r(getimagesize('./destination/jietou01-thumnail.jpg'));
    echo '</pre>';
    echo '<pre>';
    print_r($resample_res);
    echo '</pre>';
