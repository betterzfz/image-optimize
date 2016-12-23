<?php
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $file_count = $redis->get('file_count');
    $handled_count = $redis->get('handled_count');
    $redis->close();
    if ($handled_count) {
        if ($file_count == $handled_count) {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => 100]);exit;
        } else if ($file_count) {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => round($handled_count / $file_count, 2) * 100]);exit;
        } else {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => 0]);exit;
        }
    } else {
        echo json_encode(['code' => -1, 'message' => 'handle not started or finished']);exit;
    }