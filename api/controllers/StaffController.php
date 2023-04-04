<?php

    // logisticsController class extends to LogisticsModel class
    class StaffController extends StaffModel {
        public function signupNewStaff(string $fullname, int $phone_number, string $email_address, string $account_type, string $password) {
            
            // create unique id
            // instantiate App class
            $utilities = new api\utils\App();
            // verify fullname is a alpabetic string
            $verified_fullname = $utilities->checkFullname($fullname);
            // verify if phone number is a phone number
            $verified_phone_number = $utilities->checkPhoneNumber($phone_number);
            // verify if email address is a valid email
            $verified_email_address = $utilities->validateEmail($email_address);
            // check if email exist
            $email_address_exist = $this->checkEmailExist($email_address);
            // verify if account_type is merchant
            $verified_account_type = $utilities->checkAccountTypeMerchant($account_type);
            // hash/encrypt password
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

            $this->signup($verified_fullname, $verified_phone_number, $verified_email_address, $verified_account_type, $hashedPassword);
        }

        // function to add staff company_id
        public function setStaffCompanyId(string $company_id, int $id) {
            // if company_id exist in databse return error
            if ($this->checkCompanyId($company_id)) {
                # code...
                $this->setCompanyId($company_id, $id);
                return 'success';
            } else {
                return 'invalid company id';
            }

        }
    }

?>