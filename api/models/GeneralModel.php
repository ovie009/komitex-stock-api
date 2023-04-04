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
            if ($row) {
                return true;
            } else {
                return false;
            }
        }

        // function to set company_id to login table
        public function setCompanyId(string $company_id, int $id) {
            $sql = "UPDATE login SET company_id = ? WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id, $id]);
        }
    }

?>