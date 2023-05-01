<?php

    // logistics model class should extend to DatabaseConnection class and App class
    class LogisticsModel extends GeneralModel {
        
        // signup new logistics account
        // data required fullname, phone_number, email_address, account_type, comapny_id password
        protected function logisticsSignup(string $fullname, string $phone_number, string $email_address, string $account_type, string $company_id, string $password) {

            $sql = "INSERT INTO login (fullname, phone_number, email_address, account_type, company_id, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$fullname, $phone_number, $email_address, $account_type, $company_id, $password]);
        }

        // function to add new location to location table, requires company_id, location, charge
        protected function createLocation($company_id, $location, $charge) {
            $sql = "INSERT INTO location (company_id, location, charge) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id, $location, $charge]);
        }

        // function to edit location and charge where id is given
        protected function setLocation($id, $location, $charge) {
            $sql = "UPDATE location SET location = ?, charge = ?, edited_timestamp = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$location, $charge, $id]);
        }

        // insert data into location_change_history table requires location_id, company_id, location_old_name, location_new_name, price_old, price_new
        protected function createLocationChangeHistory(int $location_id, string $company_id, string $location_old_name, string $location_new_name, float $charge_old, float $charge_new) {
            $sql = "INSERT INTO location_change_history (location_id, company_id, location_old_name, location_new_name, charge_old, charge_new) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$location_id, $company_id, $location_old_name, $location_new_name, $charge_old, $charge_new]);
        }

        // function to block staff from company
        // set blocked as true in login table
        protected function blockStaff(int $staff_id, string $company_id) {
            $sql = "UPDATE login SET blocked = ? WHERE id = ? AND company_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([true, $staff_id, $company_id]);
        } 

        // function to check if a logistics have created any inventory
        // select al from inventory where company_id is given, return true if there's a result
        protected function checkForInventory(string $company_id) {
            $sql = "SELECT * FROM inventory WHERE company_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$company_id]);
            $row = $stmt->fetch();

            if ($row) return true;
            else return false;
        }
    }


?>