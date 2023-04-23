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
                $utility = new App;
                $session_token = 'KS'.$utility->createUniqueId(28);
                // set session token
                $this->setSessionToken($email_address, $session_token);
                // set login timestamp
                $this->setLoginTimestamp($email_address);
                // get user information
                $this->user = $this->getUserDetails($email_address);
                if ($utility->checkPassword($password, $this->user['password'])) {
                    // if password matches

                    // filter out ['password'] from the array
                    unset($this->user['password']);

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
            $utilities = new App();
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

            // return success
            return 'success';
        }


        // fuction to pick waybill
        public function pickWaybill(string $id, int $waybill_quantity, int $stock_id, string $product, string $company_id, string $inventory_unique_id, string $inventory_name, string $picked_by, int $user_id) {
            
            // get quantity in stock
            $stock_quanity = $this->getStockQuantity($id);
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

            return 'success';
        }

        // function to dispatch an order
        public function dispatchOrder(int $order_id, string $order_details, string $inventory_name, string $inventory_unique_id, string $company_id, int $quantity, float $price, string $location, string $product, string $multiple_product, string $dispatched_by, int $user_id) {
            // check if product exist
            if (!$this->checkProductExist($product)) return 'product doesn\'t exist';
            // check if inventory unique id exist
            if (!$this->checkInventoryUniqueId($inventory_unique_id)) return 'inventory unique id doesn\'t exist';
            // check if inventory name exist
            if (!$this->checkInventoryName($inventory_name)) return 'inventory name doesn\'t exist';
            // check if location exist
            if (!$this->checkLocationExist($location, $company_id)) return 'location doesn\'t exist';
            // check order id exist
            if (!$this->checkOrderIdExist($order_id)) return 'order id doesn\'t exist';

            // insert data into dispatch table
            $this->createDispatch($order_id, $order_details, $inventory_name, $inventory_unique_id, $company_id, $quantity, $price, $location, $product, $multiple_product, $dispatched_by);

            // get the dispatch id of data just inserted
            $dispatch_id = $this->getDispatchId($order_id);

            // by default dispatch_id in order table is NULL 
            // but after an item has been dispatched, update the dispatch_id in order table
            // set dispatch id in order table
            $this->setOrderDispatchId($order_id, $dispatch_id);

            // log this event into the activities table
            $summary = 'Order for '.$product.' dispatched to '.$location;
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $user_id, $dispatched_by);

            return 'success';
        }

        // function to add report 
        public function addReport(string $order_details, string $company_id, string $inventory_unique_id, string $inventory_name, int $stock_id, string $location, string $product, string $multiple_product, int $quantity, float $price) {

            //  get charge for a given location and given company
            $charge = $this->getCharge($location, $company_id);

            // remittance is the difference between the price and the charge for the given location
            $remittance = $price - (float)$charge;

            // create Report
            $this->createReport($order_details, $company_id, $inventory_unique_id, $inventory_name, $stock_id, $location, $product, $multiple_product, $quantity, $price, $charge, $remittance);

            return 'success';
        }

        // change preferred page on login
        public function changePreferredPage(string $preferred_page, int $user_id) {
            // set preferred page
            $this->setPreferredPage($preferred_page, $user_id);
            return 'success';
        }

        // change product image in stock requires product_image and stock_id
        public function changeProductImage(string $product_image, int $stock_id) {
            // set product image
            $this->setProductImage($stock_id, $product_image);  
            return 'success';
        }

        // function to deliver order, requires order_id and report
        public function deliverOrder(int $order_id, int $report, string $delivered_by, int $user_id) {
            // check if order id exist
            if (!$this->checkOrderIdExist($order_id)) return 'order id doesn\'t exist';
            
            // get order details
            $order = $this->getOrderDetails($order_id);
            // assign valriables to order details
            $order_details = $order['order_details'];
            $company_id = $order['company_id'];
            $inventory_unique_id = $order['inventory_unique_id'];
            $inventory_name = $order['inventory_name'];
            $stock_id = $order['stock_id'];
            $location = $order['location'];
            $product = $order['product'];
            $multiple_product = $order['multiple_product'];
            $quantity = $order['quantity'];
            $price = $order['price'];
            $dispatch_id = $order['dispatch_id'];

            $stock_quanity = $this->getStockQuantity($stock_id);
            // convert to int
            $stock_quanity = (int)$stock_quanity;
            $updated_quantity = $stock_quanity - $quantity;
            if ($updated_quantity < 0) return 'low stock quantity';

            // if dispatch_id is not equal to NULL set dispatched item as delivered
            if ($dispatch_id != NULL) {
                $this->deliverDispatch($dispatch_id);
            }
            
            // log order to report table
            $this->addReport($order_details, $company_id, $inventory_unique_id, $inventory_name, $stock_id, $location, $product, $multiple_product, $quantity, $price);

            // set order as delivered
            $this->setOrderDelivered($order_id, $report);

            // log this event into the activities table
            $summary = 'Order Delivered for '.$product.' in '.$inventory_name;
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $user_id, $delivered_by);

            return 'success';
        }

        // function to cancel order, requires order_id, report, cancelled_by, user_id
        protected function cancelOrder(int $order_id, int $report, string $cancelled_by, int $user_id) {
            // check if order id exist
            if (!$this->checkOrderIdExist($order_id)) return 'order id doesn\'t exist';
            $this->setOrderCancelled($order_id, $report);

            // get order details
            $order = $this->getOrderDetails($order_id);
            // assign valriables to order details
            $company_id = $order['company_id'];
            $inventory_unique_id = $order['inventory_unique_id'];
            $inventory_name = $order['inventory_name'];
            $product = $order['product'];

            $summary = 'Order Cancelled for '.$product.' in '.$inventory_name;
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $user_id, $cancelled_by);

            return 'success';
        }

        // function to reschedule order, requires order_id, reschedule_date, cancelled_by, user_id
        protected function rescheduleOrder(int $order_id, string $reschedule_date, string $rescheduled_by, int $user_id) {
            // check if order id exist
            if (!$this->checkOrderIdExist($order_id)) return 'order id doesn\'t exist';
            $this->setOrderRescheduled($order_id, $reschedule_date);

            // get order details
            $order = $this->getOrderDetails($order_id);
            // assign valriables to order details
            $company_id = $order['company_id'];
            $inventory_unique_id = $order['inventory_unique_id'];
            $inventory_name = $order['inventory_name'];
            $product = $order['product'];

            $summary = 'Order Reschedule for '.$product.' in '.$inventory_name.' to '.$reschedule_date;
            $this->addActivity($summary, $company_id, $inventory_unique_id, $inventory_name, $user_id, $rescheduled_by);

            return 'success';
        }

        // function to check if session_token exist, requires session_token and email_address
        public function checkSessionTokenExist(string $session_token, string $email_address) {
            // check if session token exist
            $tokenExist = $this->checkSessionToken($session_token, $email_address);

            return $tokenExist;
        }
    }

?>