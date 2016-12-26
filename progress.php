<?php
    if (is_array($_POST) && !empty($_POST)) {
        if (!$_POST['current_batch_number']) {
            echo json_encode(['code' => -1, 'message' => 'no current_batch_number']);exit;
        }
        if (!$_POST['once_number']) {
            echo json_encode(['code' => -1, 'message' => 'no once_number']);exit;
        }
        if (!$_POST['total_batch_number']) {
            echo json_encode(['code' => -1, 'message' => 'no total_batch_number']);exit;
        }
    } else {
        echo json_encode(['code' => -1, 'message' => 'no data geted']);exit;
    }
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $total_data = $redis->mGet(['total_file_count', 'total_handled_count', 'total_success_count', 'total_failure_count']);
    $total_file_count = $total_data[0];
    $total_handled_count = $total_data[1];
    $redis->close();
    if ($total_handled_count) {
        if ($total_file_count == $total_handled_count) {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => ['current_batch_number' => $_POST['current_batch_number'], 'progress' => 100, 'total_handled_count' => $total_handled_count, 'total_success_count' => $total_data[2], 'total_failure_count' => $total_data[3]]]);exit;
        } else if ($total_file_count) {
            if ($_POST['current_batch_number'] == $_POST['total_batch_number']) {
                echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => ['current_batch_number' => $_POST['current_batch_number'], 'progress' => round(($total_handled_count - $_POST['once_number'] * ($_POST['current_batch_number'] - 1)) / ($total_file_count - $_POST['once_number'] * ($_POST['current_batch_number'] - 1)), 2) * 100, 'total_handled_count' => $total_handled_count, 'total_success_count' => $total_data[2], 'total_failure_count' => $total_data[3]]]);exit;
            } else {
                echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => ['current_batch_number' => $_POST['current_batch_number'], 'progress' => round(($total_handled_count - $_POST['once_number'] * ($_POST['current_batch_number'] - 1)) / $_POST['once_number'], 2) * 100, 'total_handled_count' => $total_handled_count, 'total_success_count' => $total_data[2], 'total_failure_count' => $total_data[3]]]);exit;
            }
        } else {
            echo json_encode(['code' => 0, 'message' => 'get data successully', 'data' => ['current_batch_number' => $_POST['current_batch_number'], 'progress' => 0, 'total_handled_count' => $total_handled_count, 'total_success_count' => $total_data[2], 'total_failure_count' => $total_data[3]]]);exit;
        }
    } else {
        echo json_encode(['code' => -1, 'message' => 'handle not started or finished']);exit;
    }