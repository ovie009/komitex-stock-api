<?php

    // logisticsController class extends to LogisticsModel class
    class LogisticsController extends LogisticsModel {
        public function signupNewLogistics(string $fullname, int $phone_number, string $email_address, string $account_type, string $password) {
            
            // create unique id
            // instantiate App class
            $utilities = new api\utils\App();
            // verify fullname
            $verified_fullname = $utilities->checkFullname($fullname);
            // verify phone_number 
            $verified_phone_number = $utilities->checkPhoneNumber($phone_number);
            // verify email_address
            $verified_email_address = $utilities->validateEmail($email_address);
            // check if email address already exist
            $email_address_exist = $this->checkEmailExist($email_address);
            // verify account_type
            $verified_account_type = $utilities->checkAccountTypeLogistics($account_type);
            // generate unique company id
            $company_id = $utilities->createUniqueId(15);

            // check if company_id already exist
            while (!$this->checkCompanyId($company_id)) {
                # code...
                // if it does, generate another id
                $company_id = $utilities->createUniqueId(15);
            }
            $hashedPassword = $utilities->hashPassword($password);

            // if fullname is invalid return invalid fullname
            if (!$verified_fullname) {
                return 'invalid fullname';
            }

            // if phone_number is invalid return invalid phone_number
            if (!$verified_phone_number) {
                return 'invalid phone number';
            }

            // if email_address is invalid return invalid email_address
            if (!$verified_email_address) {
                return 'invalid email address';
            }

            // if email_address is invalid return invalid email_address
            if (!$email_address_exist) {
                return 'user already exist';
            }

            // if account_type is invalid return invalid account_type   
            if (!$verified_account_type) {
                return 'invalid account type';
            }

            $this->logisticsSignup($verified_fullname, $verified_phone_number, $verified_email_address, $verified_account_type, $company_id, $hashedPassword);
        }

        // function to add new location 
        public function addLocation(string $company_id, string $location, float $price) {
            // check if company_id already exist
            if(!$this->checkCompanyId($company_id)) return 'company id doesn\'t exist';	

            // check if location already exist
            if($this->checkLocationExist($location, $company_id)) return 'location already exist';   
            // create new location
            $this->createLocation($company_id, $location, $price);
        }

        // function to edit location and price in location table where id is given
        public function editLocation(string $id, string $location, float $price) {
            // set location
            $this->setLocation($id, $location, $price);
        }
    }

?>