<?php
    if (is_array($_POST) && !empty($_POST)) {
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
    
    $file_count = count(json_decode(file_get_contents('./batch_data.txt'), true));
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->mSet(['total_file_count' => $file_count, 'total_handled_count' => 0, 'total_success_count' => 0, 'total_failure_count' => 0]);
    $redis->close();
    echo json_encode(['code' => 0, 'message' => 'handle out', 'data' => ['file_count' => $file_count, 'batch_number' => ceil($file_count / $_POST['once_number'])]]);exit;