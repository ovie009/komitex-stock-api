<?php

    // logistics model class should extend to DatabaseConnection class and App class
    class LogisticsModel extends GeneralModel {
        
        // signup new logistics account
        // data required fullname, phone_number, email_address, account_type, comapny_id password
        public function logisticsSignup($fullname, $phone_number, $email_address, $account_type, $company_id, $password) {

            $sql = "INSERT INTO login (fullname, phone_number, email_address, account_type, company_id, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$fullname, $phone_number, $email_address, $account_type, $company_id, $password]);

            return 'success';
        }


        
    }


?>