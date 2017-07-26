<?php
    /**
     * a php class for handling image
     * @stone
     */
    class ImageClass {

        public $font = './assets/fonts/times_new_yorker.ttf'; // the type of font

        /**
         * resample the image
         * @param $source_name the source image name which may include directory.
         * @param $source_name the destination image name which may include directory, default '', destination image will override the source image when this parameter is empty.
         * @param $quality decide the quality of the destination image, default 100, ranges from 0(worst quality, smaller file) to 100(best quality, biggest file), this parameter will be ignored if the mime type of the image is image/gif.
         * @param $dest_width the width of the destination image, default 0, the width of the destination image will equal to the width of the source image when this parameter is less than or equal to 0 if parameter flag equal to 0.
         * @param $dest_height the height of the destination image, default 0, the height of the destination image will equal to the height of the source image when this parameter is less than or equal to 0 if parameter flag equal to 0.
         * @param $flag the flag of this function's behavior, default 0.  this parameter will be ignored when dest_width and dest_height are both greater than 0. the possbile values:
         * 0-no behavior
         * 1-will generate a equal scaling destination image based on dest_width and dest_height.
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
                    $quality = (100 - $quality) / 100 * 9;
                    break;
                case 'image/gif':
                    $source_image =  imagecreatefromgif($source_name);
                    $generate_func = 'imagegif';
                    $quality = -1;
                    break;           
                case 'image/jpeg': case 'image/pjpeg':
                    $source_image = imagecreatefromjpeg($source_name);
                    $generate_func = 'imagejpeg';
                    break;
                case 'image/bmp': case 'image/x-ms-bmp':
                    if (PHP_VERSION >= '7.2.0') {
                        $source_image = imagecreatefrombmp($source_name);
                        $generate_func = 'imagebmp';
                        $quality = TRUE;
                    } else {
                        $source_image = false;
                    }
                    break;
                default:
                    $source_image = false;
                    break;
            }
            if ($source_image) {
                if ($flag == 1) {
                    if ($dest_width <= 0 && $dest_height <= 0) {
                        $dest_width = $image_info[0];
                        $dest_height = $image_info[1];
                    } else if ($dest_width > 0 && $dest_height <= 0) {
                        $dest_height = round(($dest_width / $image_info[0]) * $image_info[1]);
                    } else if ($dest_width <= 0 && $dest_height > 0) {
                        $dest_width = round(($dest_height / $image_info[1]) * $image_info[0]);
                    } else {
                        if ($image_info[0] * $dest_width > $image_info[1] * $dest_height) {
                            $dest_width = round(($dest_height / $image_info[1]) * $image_info[0]);
                        } else {
                            $dest_height = round(($dest_width / $image_info[0]) * $image_info[1]);
                        }
                    }
                } else {
                    $dest_width = $dest_width > 0 ? $dest_width : $image_info[0];
                    $dest_height = $dest_height > 0 ? $dest_height : $image_info[1];
                }
                $dest_image = imagecreatetruecolor($dest_width, $dest_height);
                imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $dest_width, $dest_height, $image_info[0], $image_info[1]);
                if ($quality >= 0) {
                    $generate_res = $generate_func($dest_image, $dest_name ? $dest_name : $source_name, $quality);
                } else {
                    $generate_res = $generate_func($dest_image, $dest_name ? $dest_name : $source_name);
                }
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

        /**
         * generate captcha image
         * @param $width the width of captcha image.
         * @param $height the height of captcha image.
         * @param $code the verify code.
         * @stone
         */
        public function generate_captcha_image ($width, $height, $code) {
            $font_size = $height * 0.5; 
            $image = imagecreate($width, $height) or die('Cannot initialize new GD image stream'); 

            $background_red = mt_rand(0, 255);
            $background_green = mt_rand(0, 255);
            $background_blue = mt_rand(0, 255); 
            $background_color = imagecolorallocate($image, $background_red, $background_green, $background_blue); 
            $noise_color = imagecolorallocate($image, abs(100 - $background_red), abs(100 - $background_green), abs(100 - $background_blue)); 
            $text_color = imagecolorallocate($image, 255 - $background_red, 255 - $background_green, 255 - $background_blue); 
        
            // generate random dots in background 
            for($i = 0; $i < $width * $height / 3; $i++) { 
                imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color); 
            } 
        
            // generate random lines in background
            for($i = 0; $i < $width * $height / 150; $i++) { 
                imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color); 
            } 
        
            // set random colors
            $white_pixel = imagecolorallocate($image, abs(100 - $background_red), abs(100 - $background_green), abs(100 - $background_blue)); 
            $red_pixel = imagecolorallocate($image, abs(100 - $background_red), abs(100 - $background_green), abs(100 - $background_blue)); 
        
            // Draw a dashed line, 5 red pixels, 5 white pixels
            $style = [$red_pixel, $red_pixel, $red_pixel, $red_pixel, $red_pixel, $white_pixel, $white_pixel, $white_pixel, $white_pixel, $white_pixel]; 
            imagesetstyle($image, $style); 
            imageline($image, 0, 0, $width, $height, IMG_COLOR_STYLED); 
            imageline($image, $width, 0, 0, $height, IMG_COLOR_STYLED); 
        
            // create random polygon points
            $values = [
                mt_rand(0, $width), mt_rand(0, $height), 
                mt_rand(0, $height), mt_rand(0, $width), 
                mt_rand(0, $width), mt_rand(0, $height), 
                mt_rand(0, $height), mt_rand(0, $width), 
                mt_rand(0, $width), mt_rand(0, $height), 
                mt_rand(0, $height), mt_rand(0, $width), 
                mt_rand(0, $width), mt_rand(0, $height), 
                mt_rand(0, $height), mt_rand(0, $width), 
                mt_rand(0, $width), mt_rand(0, $height), 
                mt_rand(0, $height), mt_rand(0, $width), 
                mt_rand(0, $width), mt_rand(0, $height), 
                mt_rand(0, $height), mt_rand(0, $width),
            ]; 
        
            // create Random Colors then set it to $clr
            $red_random = abs(100 - mt_rand(0, 255)); 
            $green_random = abs(100 - mt_rand(0, 255)); 
            $blue_random = abs(100 - mt_rand(0, 255)); 
            $clr = imagecolorallocate($image, $red_random, $green_random, $blue_random); 
        
            // create filled polygon with random points 
            imagefilledpolygon($image, $values, 6, $clr); 
        
            $textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function'); 
            $x = ($width - $textbox[4]) / 2; 
            $y = ($height - $textbox[5]) / 2; 
            imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font, $code) or die('Error in imagettftext function'); 
        
            // pretty it 
            /* imageantialias($image, 100); 
            imagealphablending($image, 1); 
            imagelayereffect($image, IMG_EFFECT_OVERLAY);  */
        
            // output captcha image to browser
            header('Content-Type: image/jpeg'); 
            imagejpeg($image); 
            imagedestroy($image);
        }
    }