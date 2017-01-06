<?php
    require_once('./library/CommonClass.php');
    require_once('./library/ImageClass.php');
    $common = new CommonClass;
    $image = new ImageClass;
    session_start();
    $_SESSION['verify_code'] = $common->get_random_string(4);
    $image->generate_captcha_image(216, 60, $_SESSION['verify_code']);
?>