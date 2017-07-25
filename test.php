<?php
    /*$redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->delete(['file_count', 'handled_count', 'success_count', 'failure_count']);
    $redis->close();
    echo PHP_VERSION;
    echo PHP_VERSION >= '6.2.0';
    ini_set('memory_limit', '128M');
    require_once('./library/ImageClass.php');
    $image_object = new ImageClass;
    $resample_res = $image_object->resampleImage('./source/img0483764001432723462.bmp', './destination/img0483764001432723462.bmp', 80, 140, 105, 1);
    echo '<pre>';
    print_r($resample_res);
    echo '</pre>';
    if ($resample_res['code'] == 0) {
        echo json_encode(['code' => 0, 'message' => 'handle file successfully']);exit;
    } else {
        echo json_encode(['code' => -1, 'message' => 'handle file failure']);exit;
    }*/
    phpinfo();