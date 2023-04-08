<?php

    // logisticsController class extends to LogisticsModel class
    class MerchantController extends MerchantModel {
        // function to signup new merchant account
        public function signupMerchant(string $fullname, int $phone_number, string $email_address, string $account_type, string $password) {
            
            // create unique id
            // instantiate App class
            $utilities = new api\utils\App();
            // verify fullname is a alpabetic string
            $verified_fullname = $utilities->checkFullname($fullname);
            // verify if phone number is a phone number
            $verified_phone_number = $utilities->checkPhoneNumber($phone_number);
            // verify if email address is a valid email
            $verified_email_address = $utilities->validateEmail($email_address);
            // check if email address already exist
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

        // function to create new stock
        public function addNewStock(string $inventory_unique_id, string $company_id, string $product, int $quantity, string $details, string $product_image) {
            // check company id
            if (!$this->checkCompanyId($company_id)) return "incorrect company id";
            // check inventory unique id
            if (!$this->checkInventoryUniqueId($inventory_unique_id)) return "incorrect inventory unique id";
            // check if stock already exist
            if (!$this->checkProductExist($product)) return "product already exist";

            // let new stock quantity be equal to zero
            $new_stock_quantity = 0;
            // create stock
            $this->createStock($inventory_unique_id, $company_id, $product, $new_stock_quantity, $product_image);
            // create waybill
            $this->createWaybill($inventory_unique_id, $company_id, $product, $quantity, $details);

            return 'success';
        }

        // function to add new signatory, requires inventory_unique_id, $company_id, $fullname, $user_id
        public function addNewSignatory(string $inventory_unique_id, string $inventory_name, string $company_id, string $fullname, string $user_id) {
            // check company id
            if (!$this->checkCompanyId($company_id)) return "incorrect company id";
            // check inventory unique id
            if (!$this->checkInventoryUniqueId($inventory_unique_id)) return "incorrect inventory unique id";
            // check if signatory already exist
            if (!$this->checkSignatoryExist($inventory_unique_id, $user_id)) return "signatory already exist";

            // create signatory
            $this->createSignatory($inventory_unique_id, $inventory_name, $company_id, $fullname, $user_id);
        }

    }

?>