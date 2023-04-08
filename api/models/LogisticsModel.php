<?php

    // logistics model class should extend to DatabaseConnection class and App class
    class LogisticsModel extends GeneralModel {
        
        // signup new logistics account
        // data required fullname, phone_number, email_address, account_type, comapny_id password
        protected function logisticsSignup($fullname, $phone_number, $email_address, $account_type, $company_id, $password) {

            $sql = "INSERT INTO login (fullname, phone_number, email_address, account_type, company_id, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$fullname, $phone_number, $email_address, $account_type, $company_id, $password]);

            return 'success';
        }

        // function to add new location to location table, requires company_id, location, price
        protected function createLocation($company_id, $location, $price) {
            $sql = "INSERT INTO location (company_id, location, price) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id, $location, $price]);
        }

        // function to edit location and price where id is given
        protected function setLocation($id, $location, $price) {
            $sql = "UPDATE location SET location = ?, price = ?, edited_timestamp = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$location, $price, $id]);
        }

        
    }


?>