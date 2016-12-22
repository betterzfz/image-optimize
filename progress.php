<?php
    if (file_exists('./count_data.txt')) {
        $count_data = json_decode(file_get_contents('./count_data.txt'), true);
        if ($count_data['file_count'] == $count_data['handled_count']) {
            unlink('./count_data.txt');
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => 100]);exit;
        } else if ($count_data['file_count']) {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => round($count_data['handled_count'] / $count_data['file_count'], 2) * 100]);exit;
        } else {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => 0]);exit;
        }
    } else {
        echo json_encode(['code' => -1, 'message' => 'handle not started or finished']);exit;
    }