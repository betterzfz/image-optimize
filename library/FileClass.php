<?php
    /**
     * a php class for handling file
     * @stone
     */
    class FileClass {
        /**
         * read file from directory no depth
         * @param $directory the directory from which to read files.
         * @param $flag the flag of this function's behavior, default 0. the possbile values:
         * 0-return data has only the file name include absolute path.
         * 1-return data has both file name include absolute path and simple file name.
         * @stone
         */
        public function readFileFromDirectoryNoDepth ($directory, $flag = 0) {
            $files = [];
            if (preg_match('/^https?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $directory)) {
                $url_dir = opendir($directory);
                while (false !== ($file_name = readdir($url_dir))) {
                    if ($file_name == '.' || $file_name == '..') {
                        continue;
                    }
                    $file = $directory.'/'.$file_name;
                    if (is_dir($file)) {
                        return ['code' => -1, 'message' => $directory.' has child directory'];
                    } else {
                        if ($flag == 1) {
                            $files[] = ['absolute_path_name' => $file, 'name' => $file_name];
                        } else {
                            $files[] = $file;
                        }
                    }
                }
            } else {
                $dir_path = realpath($directory);
                $file_names = scandir($directory);
                foreach ($file_names as $file_name) {
                    if ($file_name == '.' || $file_name == '..') {
                        continue;
                    }
                    $file = $dir_path.DIRECTORY_SEPARATOR.$file_name;
                    if (is_dir($file)) {
                        continue;
                    } else {
                        if ($flag == 1) {
                            $files[] = ['absolute_path_name' => $file, 'name' => $file_name];
                        } else {
                            $files[] = $file;
                        }
                    }
                }
            }
            return ['code' => 0, 'message' => 'read file successfully', 'data' => $files];
        }

        /**
         * delete file from directory and child directory with file name.
         * @param $directory delete file from this directory.
         * @param $name the name of the file to delete.
         * @stone
         */
        public function deleteFileDepth ($directory, $name) {
            $delete_result = [];
            $dir_path = realpath($directory);
            $file_names = scandir($directory);
            foreach ($file_names as $file_name) {
                if ($file_name == '.' || $file_name == '..') {
                    continue;
                }
                $file = $dir_path.DIRECTORY_SEPARATOR.$file_name;
                if (is_dir($file)) {
                    $delete_result = $this->deleteFileDepth($file, $name);
                }
            }
            $this_file_name = $dir_path.DIRECTORY_SEPARATOR.$name;
            if (file_exists($this_file_name)) {
                $delete_result[] = ['name' => $this_file_name, 'delete_result' => unlink($this_file_name)];
            } else {
                $delete_result[] = ['name' => $this_file_name, 'delete_result' => 'file is not exists'];
            }
            return $delete_result;
        }
    }