<?php
    ini_set('memory_limit', '1024M');
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
    $batch_data = json_decode(file_get_contents('./batch_data.txt'), true);
    require_once('./library/ImageClass.php');
    $image_object = new ImageClass;
    $destination_directory = realpath($_POST['destination_directory']);
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $this_handled_count = 0;
    foreach ($batch_data as $key => $file) {
        $resample_res = $image_object->resampleImage($file['absolute_path_name'], $destination_directory.DIRECTORY_SEPARATOR.$file['name'], $_POST['quality'], $_POST['dest_width'], $_POST['dest_height'], $_POST['flag']);
        if ($resample_res['code'] == 0) {
            $redis->incr('total_handled_count');
            $redis->incr('total_success_count');
        } else {
            file_put_contents('./log.txt', date('Y-m-d H:i:s').':'.$file['absolute_path_name'].':'.json_encode($resample_res)."\n", FILE_APPEND);
            $redis->incr('total_handled_count');
            $redis->incr('total_failure_count');
        }
        $this_handled_count++;
        unset($batch_data[$key]);
        if ($this_handled_count == $_POST['once_number']) {
            break;
        }
    }
    $redis->close();
    file_put_contents('./batch_data.txt', json_encode($batch_data));
    echo json_encode(['code' => 0, 'message' => 'handle out']);exit;