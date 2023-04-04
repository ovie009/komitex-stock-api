<?php

    // generalController class

    class GeneralController extends GeneralModel {

        public $user;
        // login function, requires email address and passwerd
        public function login (string $email_address, string $password) {
            // check if user exist
            if (!$this->checkEmailExist($email_address)) return 'user doesn\'t exist';
            else {
                // if user exist
                $this->user = $this->getUserDetails($email_address);
                $utility = new api\utils\App;
                if ($utility->checkPassword($password, $this->user['password'])) {
                    // if password matches
                    $this->setLoginTimestamp($email_address);
                    // return user
                    return $this->user;
                } else {
                    // else return incorrect password
                    return 'incorrect password';
                }
            }
        }
    }

?>