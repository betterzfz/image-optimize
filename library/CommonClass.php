<?php
    /**
     * a php class for frequently used functions
     * @stone
     */
    class CommonClass {
        const LOWER_CASE_LETTERS = 'abcdefghijklmnopqrstuvwxyz'; // a class constant include lower-case letters
        const CAPITAL_LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // a class constant include capital letters
        const NUMBERS = '0123456789'; // a class constant include numbers

        /**
         * a function to generate a random string
         * @param length the length of the random string
         * @param flag
         * 1-get only lower-case letters string,
         * 2-get only capital letters string,
         * 3-get only letter string, 
         * 4-get numeric string,
         * 5-get a string is made up of lower-case letters and numbers,
         * 6-get a string is made up of capital letters and numbers,
         * otherwise you will get a string is made up of case letters and numbers
         * @stone
         */
        public function get_random_string ($length, $flag = 0) {
            $candidate_characters = '';
            switch ($flag) {
                case 1:
                    $candidate_characters = self::LOWER_CASE_LETTERS;
                    break;
                case 2:
                    $candidate_characters = self::CAPITAL_LETTERS;
                    break;
                case 3:
                    $candidate_characters = self::LOWER_CASE_LETTERS.self::CAPITAL_LETTERS;
                    break;
                case 4:
                    $candidate_characters = self::NUMBERS;
                    break;
                case 5:
                    $candidate_characters = self::LOWER_CASE_LETTERS.self::NUMBERS;
                    break;
                case 6:
                    $candidate_characters = self::CAPITAL_LETTERS.self::NUMBERS;
                    break;
                default:
                    $candidate_characters = self::LOWER_CASE_LETTERS.self::CAPITAL_LETTERS.self::NUMBERS;
                    break;
            }

            $result_string = '';
            $random_length = strlen($candidate_characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $result_string .= $candidate_characters[rand(0, $random_length)];
            }
            return $result_string;
        }
    }