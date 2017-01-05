<?php
    require_once('./library/CommonClass.php');
    require_once('./library/ImageClass.php');
    $common = new CommonClass;
    $image = new ImageClass;
    $image->generate_captcha_image(300, 80, $common->get_random_string(4));
?>