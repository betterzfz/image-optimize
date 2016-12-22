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
    if (!is_dir($_POST['destination_directory'])) {
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
        $count_data = [
            'file_count' => count($files['data']),
            'handled_count' => 0,
            'success_count' => 0,
            'failure_count' => 0,
        ];
        foreach ($files['data'] as $file) {
            $resample_res = $image_object->resampleImage($file['absolute_path_name'], $destination_directory.DIRECTORY_SEPARATOR.$file['name'], $_POST['quality'], $_POST['dest_width'], $_POST['dest_height'], $_POST['flag']);
            if ($resample_res['code'] == 0) {
                $count_data['handled_count']++;
                $count_data['success_count']++;
            } else {
                file_put_contents('./log.txt', date('Y-m-d H:i:s').':'.json_encode($resample_res)."\n", FILE_APPEND);
                $count_data['handled_count']++;
                $count_data['failure_count']++;
            }
            file_put_contents('./count_data.txt', json_encode($count_data));
        }
        echo json_encode(['code' => 0, 'message' => 'handle over', 'data' => $count_data]);exit;
    } else {
        echo json_encode($files);exit;
    }