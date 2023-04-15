<?php

    // logisticsController class extends to LogisticsModel class
    class LogisticsController extends LogisticsModel {
        public function signupNewLogistics(string $fullname, int $phone_number, string $email_address, string $account_type, string $password) {
            
            // create unique id
            // instantiate App class
            $utilities = new App();
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
        public function addLocation(string $company_id, string $location, float $charge, string $fullname, int $user_id) {
            // check if company_id already exist
            if(!$this->checkCompanyId($company_id)) return 'company id doesn\'t exist';	

            // check if location already exist
            if($this->checkLocationExist($location, $company_id)) return 'location already exist';   
            // create new location
            $this->createLocation($company_id, $location, $charge);

            // log this event into the activities table
            $summary = 'New location added, '.$location;
            $inventory_unique_id = '';
            $inventory_name = '';

            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $fullname, $user_id);
        }

        // function to edit location and charge in location table where id is given
        public function editLocation(int $location_id, string $company_id, string $location_old_name, float $charge_old, string $location_new_name, float $charge_new, string $fullname, int $user_id) {
            
            // check if company_id already exist
            if(!$this->checkCompanyId($company_id)) return 'company id doesn\'t exist';

            // set location 
            $this->setLocation($location_id, $location_new_name, $charge_new);
            // add location change history
            $this->createLocationChangeHistory($location_id, $company_id, $location_old_name, $location_new_name, $charge_old, $charge_new);

            if ($location_new_name === $location_old_name) {
                // summmary text if locations are the same
                $summary = $location_new_name.' charge changed from '.$charge_old.' to '.$charge_new;
            } else {
                // sumary text if locations are different
                $summary = 'location details changed from '.$location_old_name.' to '.$location_new_name;
                if ($charge_new !== $charge_old) {
                    // if charge changed
                    $summary .= ' and charge changed from '.$charge_old.' to '.$charge_new;
                }
            }
            $inventory_unique_id = '';
            $inventory_name = '';

            // log this event into the activities table
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $fullname, $user_id);
        }

        // function to block staff
        public function blockMyStaff(int $staff_id, int $company_id) {
            $this->blockStaff($staff_id, $company_id);
        }
    }

?>