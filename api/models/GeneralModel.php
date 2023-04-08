<?php

    class GeneralModel extends DatabaseConnection {

        // signup new staff or merchant account
        // data required fullname, phone_number, email_address, account_type, password
        public function signup(string $fullname, int $phone_number, string $email_address, string $account_type, string $password) {

            $sql = "INSERT INTO login (fullname, phone_number, email_address, account_type, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$fullname, $phone_number, $email_address, $account_type, $password]);
        }

        // function to check if company_id already exist in login table
        public function checkCompanyId($company_id) {
            $sql = "SELECT * FROM login WHERE company_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id]);
            $row = $stmt->fetch();

            // if there is a result return true, else return false
            if ($row) return true;
            else return false;
        }

        // function to set company_id to login table
        public function setCompanyId(string $company_id, int $id) {
            $sql = "UPDATE login SET company_id = ? WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id, $id]);
        }

        // function to select all from login table except password where email_address and password match function parameter
        public function getUserDetails(string $email_address) {
            $sql = "SELECT * FROM login WHERE email_address = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email_address]);
            $row = $stmt->fetch();
            return $row;
        }

        // function to check if email exist in login table
        public function checkEmailExist(string $email) {
            $sql = "SELECT * FROM login WHERE email = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email]);
            $row = $stmt->fetch();
            if ($row) return true;
            else return false;
            
        }

        // function to set login_timestamp as CURRENT_TIMESTAMP where email is given
        public function setLoginTimestamp(string $email) {
            $sql = "UPDATE login SET login_timestamp = CURRENT_TIMESTAMP WHERE email = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email]);
        }

        // function to add data to activities table
        // columns required summary, company_id, inventory_unique_id, initiator
        public function addActivity(string $summary, string $company_id, string $inventory_unique_id, string $initiator) {
            $sql = "INSERT INTO activities (summary, company_id, inventory_unique_id, initiator) VALUES (?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$summary, $company_id, $inventory_unique_id, $initiator]);
        }

        // function to create new inventory slot, 
        // inventory slots can only be created by logistics or staff
        // requires inventory_unique_id, inventory_name, company_id
        public function createInventory(string $inventory_unique_id, string $inventory_name, string $company_id) {
            $sql = "INSERT INTO inventory (inventory_unique_id, inventory_name, company_id) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $inventory_name, $company_id]);
        }

        // function to check if inventory unique id already exist in inventory table
        public function checkInventoryUniqueId(string $inventory_unique_id) {
            $sql = "SELECT * FROM inventory WHERE inventory_unique_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id]);
            $row = $stmt->fetch();

            if ($row) return true;
            else return false;
            
        }
        
        // function to check if inventory_name exist in inventory table
        public function checkInventoryName(string $inventory_name) {
            $sql = "SELECT * FROM inventory WHERE inventory_name = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_name]);
            $row = $stmt->fetch();
    
            if ($row) return true;
            else return false;
            
        }
        
    }

?>