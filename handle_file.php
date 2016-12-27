<?php
    if (is_array($_POST) && !empty($_POST)) {
        if (!$_POST['source_file']) {
            echo json_encode(['code' => -1, 'message' => 'no source_file']);exit;
        }
        if (!$_POST['destination_directory']) {
            echo json_encode(['code' => -1, 'message' => 'no destination_directory']);exit;
        }
        if ($_POST['quality'] < 0 || $_POST['quality'] > 100) {
            echo json_encode(['code' => -1, 'message' => 'invalid quality']);exit;
        }
    } else {
        echo json_encode(['code' => -1, 'message' => 'no data posted']);exit;
    }
    if (!is_dir($_POST['destination_directory']) && !preg_match('/^https?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $_POST['destination_directory'])) {
        if (!mkdir($_POST['destination_directory'], 0777, true)) {
            echo json_encode(['code' => -1, 'message' => $_POST['destination_directory'].'is not exists and could not make it']);exit;
        }
    }
    
    require_once('./library/ImageClass.php');
    $image_object = new ImageClass;
    $resample_res = $image_object->resampleImage($_POST['source_file'], $_POST['destination_directory'].pathinfo($_POST['source_file'])['basename'], $_POST['quality'], $_POST['dest_width'], $_POST['dest_height'], $_POST['flag']);
    if ($resample_res['code'] == 0) {
        echo json_encode(['code' => 0, 'message' => 'handle file successfully']);exit;
    } else {
        echo json_encode(['code' => -1, 'message' => 'handle file failure']);exit;
    }