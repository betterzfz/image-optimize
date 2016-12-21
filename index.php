<?php
    require_once('./library/ImageClass.php');
    $image_object = new ImageClass;
    $resample_res = $image_object->resampleImage('./source/jietou01.jpg', './destination/jietou01.jpg', 50);
    echo '<pre>';
    print_r(getimagesize('./destination/jietou01.jpg'));
    echo '</pre>';
    // echo '<pre>';
    // print_r(getimagesize('./destination/jietou01.jpg'));
    // echo '</pre>';
