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

        // function to create inventory
        public function createNewInventory(string $inventory_unique_id, string $inventory_name, string $company_id, string $fullname) {
            // create unique id
            $utilities = new api\utils\App();
            $inventory_unique_id = $utilities->createUniqueId(15);
            // create inventory
            // while inventory unique id exist, create another unique id
            while ($this->checkInventoryUniqueId($inventory_unique_id)) {
                # code...
                $inventory_unique_id = $utilities->createUniqueId(15);
            }

            if ($this->checkInventoryName($inventory_name)) return 'inventory name already exist';

            // if inventory name doesn't exist, create new inventory
            $this->createInventory($inventory_unique_id, $inventory_name, $company_id);

            // log this event into the activities table
            $summary = 'New inventory created, '.$inventory_name;

            $this->addActivity($summary, $company_id, $inventory_unique_id, $fullname);
        }

    }

?>