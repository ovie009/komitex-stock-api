<?php

    namespace api\utils;

    class App {

        // method to create unique id with all alphaNumeric characters and recieves a parameter to determine the lenght of the unique id
        public function createUniqueId(int $length) {
            $alphanumeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $uniqueId = '';
            for ($i = 0; $i < $length; $i++) {
                $uniqueId .= $alphanumeric[rand(0, strlen($alphanumeric) - 1)];
            }
            return $uniqueId;
        }

        // method to receive a string as password and return the hash of the password
        public function hashPassword(string $password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        // function to check if a received string, fullname matches the reg expression of at least 3 words of only alphabetic characters, -, space and '
        public function checkFullname(string $fullname) {
            return preg_match("/^[a-zA-Z-' ]{3,}$/", $fullname);
        }

        // function to check phonenumber reg expression of atleast 11 digits but maximum of 13
        public function checkPhoneNumber(string $phone_number) {
            return preg_match("/^[0-9]{11,13}$/", $phone_number);
        }

        // function to validate email
        public function validateEmail($email_address) {
            return filter_var($email_address, FILTER_VALIDATE_EMAIL);
        }

        // function to check if account_type is logistics
        public function checkAccountTypeLogistics(string $account_type) {
            if ($account_type === 'logistics') {
                # code...
                return true;
            } else {
                return false;
            }
        }

        // function to check if account_type is merchant
        public function checkAccountTypeMerchant(string $account_type) {
            if ($account_type === 'merchant') {
                # code...
                return true;
            } else {
                return false;
            }
        }

        // function to check if account_type is staff
        public function checkAccountTypeStaff(string $account_type) {
            if ($account_type === 'staff') {
                # code...
                return true;
            } else {
                return false;
            }
        }

        // function to compare hashed password and password provided by user
        public function checkPassword(string $password, string $hashed_password) {
            return password_verify($password, $hashed_password);
        }

    }

?>