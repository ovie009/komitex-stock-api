<?php

    class GeneralModel extends DatabaseConnection {

        // signup new staff or merchant account
        // data required fullname, phone_number, email_address, account_type, password
        protected function signup(string $fullname, int $phone_number, string $email_address, string $account_type, string $password) {

            $sql = "INSERT INTO login (fullname, phone_number, email_address, account_type, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$fullname, $phone_number, $email_address, $account_type, $password]);
        }

        // function to check if company_id already exist in login table
        protected function checkCompanyId($company_id) {
            $sql = "SELECT * FROM login WHERE company_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id]);
            $row = $stmt->fetch();
            // if there is a result return true, else return false
            if ($row) return true;
            else return false;
        }

        // function to set company_id to login table
        protected function setCompanyId(string $company_id, int $id) {
            $sql = "UPDATE login SET company_id = ? WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id, $id]);
        }

        // function to select all from login table except password where email_address and password match function parameter
        protected function getUserDetails(string $email_address) {
            $sql = "SELECT * FROM login WHERE email_address = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email_address]);
            $row = $stmt->fetch();
            return $row;
        }

        // function to check if email exist in login table
        protected function checkEmailExist(string $email) {
            $sql = "SELECT * FROM login WHERE email = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email]);
            $row = $stmt->fetch();
            // if exist, return true else return false
            if ($row) return true;
            else return false;  
        }

        // function to set login_timestamp as CURRENT_TIMESTAMP where email is given
        protected function setLoginTimestamp(string $email) {
            $sql = "UPDATE login SET login_timestamp = CURRENT_TIMESTAMP WHERE email = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email]);
        }

        // function to add data to activities table
        // columns required summary, company_id, inventory_unique_id, initiator
        protected function addActivity(string $summary, string $company_id, string $inventory_unique_id, string $inventory_name, string $initiator, int $user_id) {
            $sql = "INSERT INTO activities (summary, company_id, inventory_unique_id, inventory_name, initiator, user_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$summary, $company_id, $inventory_unique_id, $inventory_name, $initiator, $user_id]);
        }

        // function to create new inventory slot, 
        // inventory slots can only be created by logistics or staff
        // requires inventory_unique_id, inventory_name, company_id
        protected function createInventory(string $inventory_unique_id, string $inventory_name, string $company_id) {
            $sql = "INSERT INTO inventory (inventory_unique_id, inventory_name, company_id) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $inventory_name, $company_id]);
        }

        // function to check if inventory unique id already exist in inventory table
        protected function checkInventoryUniqueId(string $inventory_unique_id) {
            $sql = "SELECT * FROM inventory WHERE inventory_unique_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id]);
            $row = $stmt->fetch();
            // if exist, return true else return false
            if ($row) return true;
            else return false;
            
        }
        
        // function to check if inventory_name exist in inventory table
        protected function checkInventoryName(string $inventory_name) {
            $sql = "SELECT * FROM inventory WHERE inventory_name = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_name]);
            $row = $stmt->fetch();
            // if exist, return true else return false
            if ($row) return true;
            else return false;
            
        }

        // function to pick waybill
        //  set status in waybill table to received, can only be accessed by logistics and staff, require id
        protected function receiveWaybill(string $id) {
            $sql = "UPDATE waybill SET status = 'received' WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$id]);
        }

        // check if waybill is already received
        // get data if status is received in waybill table where id is given
        protected function checkWaybillReceived(string $id) {
            $sql = "SELECT * FROM waybill WHERE id = ? AND status = 'received'";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            // if exist, return true else return false
            if ($row) return true;
            else return false;
        }


        // function  to set quantity in stock table where id and quantity is given, can be accessed by all account types
        protected function setQuantity(string $stock_id, int $quantity) {
            $sql = "UPDATE stock SET quantity = ? WHERE stock_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$quantity, $stock_id]);
        }

        // function to get quantity from stock table where id is given, can be accessed by all account types
        protected function getQuantity(string $id) {
            $sql = "SELECT quantity FROM stock WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            return $row;
        }

        // function to input data into orders table, requires order_details, product, multiple_products, company_id, stock_id, inventory_unique_id, inventory_name, quantity, price, location
        protected function createOrder(string $order_details, string $product, string $multiple_products, string $company_id, int $stock_id, string $inventory_unique_id, string $inventory_name, int $quantity, float $price, string $location) {
            $sql = "INSERT INTO orders (order_details, product, multiple_products, company_id, stock_id, inventory_unique_id, inventory_name, quantity, price, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$order_details, $product, $multiple_products, $company_id, $stock_id, $inventory_unique_id, $inventory_name, $quantity, $price, $location]);
        }

        // function to check if product already exist in stock
        protected function checkProductExist(string $product) {
            $sql = "SELECT * FROM stock WHERE product = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$product]);
            $row = $stmt->fetch();
            if ($row) return true;
            else return false;
        }

        // function to check stock_id exist in stock table
        protected function checkStockIdExist(string $stock_id) {
            $sql = "SELECT * FROM stock WHERE stock_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$stock_id]);
            $row = $stmt->fetch();
            // if result exist, return true else return false
            if ($row) return true;
            else return false;
        }

        // function to check if quantity in stock table is greater than or equal to given quantity
        protected function checkQuantity(string $stock_id, int $quantity) {
            $sql = "SELECT * FROM stock WHERE stock_id = ? AND quantity >= ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$stock_id, $quantity]);
            $row = $stmt->fetch();
            // if result exist, return true else return false
            if ($row) return true;
            else return false;
        }

        // function to check if location exist in location table where location and company_id is given
        protected function checkLocationExist(string $location, string $company_id) {
            $sql = "SELECT * FROM location WHERE location = ? AND company_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$location, $company_id]);
            $row = $stmt->fetch();
            // if result exist, return true else return false
            if ($row) return true;
            else return false;
        }

        // function to insert data into dispatch table, requires, order_details, inventory_name, inventory_unique_id, company_id, quantity, price, location, product, multiple_product, dispatched_by
        protected function createDispatch(string $order_details, string $inventory_name, string $inventory_unique_id, string $company_id, int $quantity, float $price, string $location, string $product, string $multiple_product, string $dispatched_by) {
            $sql = "INSERT INTO dispatch (order_details, inventory_name, inventory_unique_id, company_id, quantity, price, location, product, multiple_product, dispatched_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$order_details, $inventory_name, $inventory_unique_id, $company_id, $quantity, $price, $location, $product, $multiple_product, $dispatched_by]);
        }

        // function to insert dat to report table, requires order_details, company_id, inventory_unique_id, inventory_name, stock_id, product, multiple_product, quantity, price, charge, remittance
        protected function createReport(string $order_details, string $company_id, string $inventory_unique_id, string $inventory_name, int $stock_id, string $location, string $product, string $multiple_product, int $quantity, float $price, float $charge, float $remittance) {
            $sql = "INSERT INTO report (order_details, company_id, inventory_unique_id, inventory_name, stock_id, location, product, multiple_product, quantity, price, charge, remittance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$order_details, $company_id, $inventory_unique_id, $inventory_name, $stock_id, $location, $product, $multiple_product, $quantity, $price, $charge, $remittance]);
        }

        // function to get price from location table where location and company_id is given
        protected function getCharge(string $location, string $company_id) {
            $sql = "SELECT charge FROM location WHERE location = ? AND company_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$location, $company_id]);
            $row = $stmt->fetch();
            return $row['charge'];
        }
    }

?>