<?php

    // logisticsController class extends to LogisticsModel class
    class MerchantController extends MerchantModel {

        public $user;

        // function to signup new merchant account
        public function signupMerchant(string $fullname, int $phone_number, string $email_address, string $account_type, string $password, string $retype_password) {

            // instantiate App class
            $utilities = new App();
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
            if ($email_address_exist) {
                return 'email already exist';
            }

            // if account_type is invalid return invalid account_type   
            if (!$verified_account_type) {
                return 'invalid account type';
            }

            // check if password and retype_password match
            if ($password != $retype_password) {
                return 'password does not match';
            }

            $this->signup($fullname, $phone_number, $email_address, $account_type, $hashedPassword);

            $session_token = 'KS'.$utilities->createUniqueId(28);
            // set session token
            $this->setSessionToken($email_address, $session_token);
            // set login timestamp
            $this->setLoginTimestamp($email_address);
            // get user information
            $this->user = $this->getUserDetails($email_address);

            // filter out ['password'] from the array
            unset($this->user['password']);

            // return user
            return $this->user;
        }

        // function to create new stock
        public function addNewStock(string $inventory_unique_id, string $inventory_name, int $stock_id, string $company_id, string $product, int $quantity, string $details, string $product_image, string $initiator, int $user_id) {
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
            $this->createWaybill($inventory_unique_id, $stock_id, $company_id, $product, $quantity, $details);

            $summary = 'New Product '.$product.'('.$quantity.') has been added to '.$inventory_unique_id;

            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $initiator, $user_id);

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

            $summary = 'New Signatory '.$fullname.', added to  '.$inventory_name;

            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $fullname, $user_id);

            return 'success';
        }

        // function to edit product name requires product_new_name, id, stock_id, product_old_name, user_id, fullname, inventory_unique_id, company_id
        public function editProductName(string $product_new_name, string $id, int $stock_id, string $product_old_name, int $user_id, string $changed_by, string $inventory_unique_id, string $inventory_name, string $company_id) {
            // check company id
            if (!$this->checkCompanyId($company_id)) return "incorrect company id";
            // check inventory unique id
            if (!$this->checkInventoryUniqueId($inventory_unique_id)) return "incorrect inventory unique id";
            // check if product_new_name exist
            if (!$this->checkProductExist($product_new_name)) return "product new name does not exist";

            // set new product name
            $this->setProductName($id, $product_new_name);
            // log change into product name change history
            $this->createProductNameChangeHistory($stock_id, $product_new_name, $product_old_name, $user_id, $changed_by, $inventory_unique_id, $inventory_name, $company_id);

            $summary = 'Product name changed from '.$product_old_name.' to '.$product_new_name.' in '.$inventory_name.' inventory';

            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $changed_by, $user_id);

            return 'success';
        }

        // function to delete unreceived waybill only
        public function deleteUnreceivedWaybill(int $id) {
            // check if waybill has been received
            if ($this->checkWaybillReceived($id)) return 'waybill has already been received';
            // delete waybill
            $this->deleteWaybill($id);
        }

        // function to block signatory from inventory
        public function blockInventorySignatory(int $user_id, string $inventory_unique_id) {
            $this->blockSignatory($user_id, $inventory_unique_id);
        }

        public function test() {
            $utilities = new App();
            $test = $utilities->test_variable;

            return $test;
        }
    }

?>