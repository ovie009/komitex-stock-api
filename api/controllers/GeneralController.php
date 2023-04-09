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
        public function addInventory(string $inventory_unique_id, string $inventory_name, string $company_id, string $fullname, int $user_id) {
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

            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $fullname, $user_id);
        }


        // fuction to pick waybill
        public function pickWaybill(string $id, int $waybill_quantity, int $stock_id, string $product, string $company_id, string $inventory_unique_id, string $inventory_name, string $picked_by, int $user_id) {
            
            // get quantity in stock
            $stock_quanity = $this->getQuantity($id);
            // convert to int
            $stock_quanity = (int)$stock_quanity;
            $updated_quantity = $stock_quanity + $waybill_quantity;

            // check if waybill is already received
            if ($this->checkWaybillReceived($id)) return 'waybill already received';
            
            // activity summary
            $summary = $waybill_quantity.' of '.$product.' picked for '.$inventory_name;
            // set waybill as received
            $this->receiveWaybill($id);
            // set quantity in stock
            $this->setQuantity($stock_id, $updated_quantity);
            // add activity to activities table
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $picked_by, $user_id);
            // return success
            return 'success';
        }

        // function to send a new order to logistics
        public function sendNewOrder(string $order_details, string $product, string $multiple_products, string $company_id, int $stock_id, string $inventory_unique_id, string $inventory_name, int $quantity, float $price, string $location, string $initiator, int $initiator_id) {
            // check if product exist
            if (!$this->checkProductExist($product)) return 'product doesn\'t exist';
            // check if inventory unique id exist
            if (!$this->checkInventoryUniqueId($inventory_unique_id)) return 'inventory unique id doesn\'t exist';
            // check if inventory name exist
            if (!$this->checkInventoryName($inventory_name)) return 'inventory name doesn\'t exist';
            // check if stock id exist
            if (!$this->checkStockIdExist($stock_id)) return 'stock id doesn\'t exist';
            // check if quantity exist
            if (!$this->checkQuantity($stock_id, $quantity)) return 'quantity left in stock in less than quanity in order details';
            // check if location exist
            if (!$this->checkLocationExist($location, $company_id)) return 'location doesn\'t exist';

            // create order
            $this->createOrder($order_details, $product, $multiple_products, $company_id, $stock_id, $inventory_unique_id, $inventory_name, $quantity, $price, $location);

            // log this event into the activities table
            $summary = 'New order posted for '.$product.' in '.$inventory_name.' inventory';
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $initiator, $initiator_id);

        }

        // function to dispatch an order
        public function dispatchOrder(string $order_details, string $inventory_name, string $inventory_unique_id, string $company_id, int $quantity, float $price, string $location, string $product, string $multiple_product, string $dispatched_by, int $user_id) {
            // check if product exist
            if (!$this->checkProductExist($product)) return 'product doesn\'t exist';
            // check if inventory unique id exist
            if (!$this->checkInventoryUniqueId($inventory_unique_id)) return 'inventory unique id doesn\'t exist';
            // check if inventory name exist
            if (!$this->checkInventoryName($inventory_name)) return 'inventory name doesn\'t exist';
            // check if location exist
            if (!$this->checkLocationExist($location, $company_id)) return 'location doesn\'t exist';

            $this->createDispatch($order_details, $inventory_name, $inventory_unique_id, $company_id, $quantity, $price, $location, $product, $multiple_product, $dispatched_by);

            // log this event into the activities table
            $summary = 'Order for '.$product.' dispatched to '.$location;
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $user_id, $dispatched_by);
            
        }

        // function to add report 
        public function addReport(string $order_details, string $company_id, string $inventory_unique_id, string $inventory_name, int $stock_id, string $location, string $product, string $multiple_product, int $quantity, float $price) {

            //  get charge for a given location and given company
            $charge = $this->getCharge($location, $company_id);

            // remittance is the difference between the price and the charge for the given location
            $remittance = $price - (float)$charge;

            // create Report
            $this->createReport($order_details, $company_id, $inventory_unique_id, $inventory_name, $stock_id, $location, $product, $multiple_product, $quantity, $price, $charge, $remittance);
        }
    }

?>