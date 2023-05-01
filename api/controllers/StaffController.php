<?php

    // logisticsController class extends to LogisticsModel class
    class StaffController extends StaffModel {
        public function signupStaff(string $fullname, string $username, int $phone_number, string $email_address, string $account_type, string $password, string $retype_password) {
            
            // create unique id
            // instantiate App class
            $utilities = new App();
            // verify fullname is a alpabetic string
            $verified_fullname = $utilities->checkFullname($fullname);
            // verify if phone number is a phone number
            $verified_phone_number = $utilities->checkPhoneNumber($phone_number);
            // verify if email address is a valid email
            $verified_email_address = $utilities->validateEmail($email_address);
            // check if email exist
            $email_address_exist = $this->checkEmailExist($email_address);
            // check if username exist
            $username_exist = $this->checkUsernameExist($username);
            // verify if account_type is merchant
            $verified_account_type = $utilities->checkAccountTypeStaff($account_type);
            // hash/encrypt password
            $hashedPassword = $utilities->hashPassword($password);

            // if space is in username string
            $space_in_username = $utilities->checkUsernameCharacters($username);

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
            if ($email_address_exist) {
                return 'email already exist';
            }

            // if username already exist return invalid username
            if ($username_exist) {
                return 'username already exist';
            }

            // if username string has space
            if ($space_in_username) {
                return 'Usernames should not have the character space \' \' ';
            }

            // if account_type is invalid return invalid account_type   
            if (!$verified_account_type) {
                return 'invalid account type';
            }
            
            // check if password and retype_password match
            if ($password != $retype_password) {
                return 'password does not match';
            }	

            $this->signup($fullname, $username, $phone_number, $email_address, $account_type, $hashedPassword);

            return 'success';
        }

        // function to add staff company_id
        public function setStaffCompanyId(string $company_id, int $id, string $fullname, string $user_id) {
            // if company_id exist in databse return error
            if ($this->checkCompanyId($company_id)) {
                # code...

                $inventory_unique_id = '';
                $inventory_name = '';
                $summary = $fullname.' joined your team';	

                // set company_id to login table
                $this->setCompanyId($company_id, $id);

                // staff joins team activity
                $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $fullname, $user_id);
                return 'success';
            } else {
                return 'invalid company id';
            }
        }
    }

?>