<?php
    if (is_array($_POST) && !empty($_POST)) {
        if (!$_POST['source_directory']) {
            echo json_encode(['code' => -1, 'message' => 'no source_directory']);exit;
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
    require_once('./library/FileClass.php');
    $file_object = new FileClass;
    $files = $file_object->readFileFromDirectoryNoDepth($_POST['source_directory'], 1);
    if ($files['code'] == 0) {
        require_once('./library/ImageClass.php');
        $image_object = new ImageClass;
        $destination_directory = realpath($_POST['destination_directory']);
        file_put_contents('./batch_data.txt', json_encode($files['data']));
        $file_count = count($files['data']);
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->mSet(['total_file_count' => $file_count, 'total_handled_count' => 0, 'total_success_count' => 0, 'total_failure_count' => 0]);
        $redis->close();
        echo json_encode(['code' => 0, 'message' => 'handle out', 'data' => ['file_count' => $file_count, 'batch_number' => ceil($file_count / $_POST['once_number'])]]);exit;
    } else {
        echo json_encode($files);exit;
    }