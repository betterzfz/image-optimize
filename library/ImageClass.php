<?php
    class ImageClass {
        /*
         * resample the image
         * @param $source_name the source image name which may include directory.
         * @param $source_name the destination image name which may include directory, default '', destination image will override the source image when this paramater is empty.
         * @param $quality decide the quality of the destination image, default 100, ranges from 0(worst quality, smaller file) to 100(best quality, biggest file), this paramater will be ignored if the mime type of the image is image/gif.
         * @param $dest_width the width of the destination image, default 0, the width of the destination image will equal to the width of the source image when this paramater is less than or equal to 0 if paramater flag equal to 0.
         * @param $dest_height the height of the destination image, default 0, the height of the destination image will equal to the height of the source image when this paramater is less than or equal to 0 if paramater flag equal to 0.
         * @param $flag the flag of this function's behavior, default 0. the possbile values:
         * 0-no behavior
         * 1-will generate a equal scaling destination image based on dest_width or dest_height when dest_width or dest_height is greater than 0.
         * @stone
         */
        public function resampleImage ($source_name, $dest_name = '', $quality = 100, $dest_width = 0, $dest_height = 0, $flag = 0) {
            if (!file_exists($source_name)) {
                return [
                    'code' => -3,
                    'message' => 'source image is not found'
                ];
            }
            $image_info = getimagesize($source_name);
            switch ($image_info['mime']) {
                case 'image/png':
                    $source_image =  imagecreatefrompng($source_name);
                    $generate_func = 'imagepng';
                    break;
                case 'image/gif':
                    $source_image =  imagecreatefromgif($source_name);
                    $generate_func = 'imagegif';
                    break;           
                case 'image/jpeg': case 'image/pjpeg':
                    $source_image = imagecreatefromjpeg($source_name);
                    $generate_func = 'imagejpeg';
                    break;
                default:
                    $source_image = false;
            }
            if ($source_image) {
                $dest_width = $dest_width > 0 ? $dest_width : $image_info[0];
                $dest_height = $dest_height > 0 ? $dest_height : $image_info[1];
                $dest_image = imagecreatetruecolor($dest_width, $dest_height);
                imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $dest_width, $dest_height, $image_info[0], $image_info[1]);
                $generate_res = $generate_func($dest_image, $dest_name ? $dest_name : $source_name, $quality);
                imagedestroy($dest_image);
                if ($generate_res) {
                    return [
                        'code' => 0,
                        'message' => 'resample image successfully!'
                    ];
                } else {
                    return [
                        'code' => -2,
                        'message' => 'resample image failure!'
                    ];
                }
            } else {
                return [
                    'code' => -1,
                    'message' => 'unknown or not be supported image mime'
                ];
            }
        }
    }