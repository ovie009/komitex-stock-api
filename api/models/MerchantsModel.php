<?php

    // merchant model class should extend to DatabaseConnection class and App class

    class MerchantModel extends GeneralModel {
        
        // function to add data to inventory_signatory table, requires inventory_unique_id, company_id, fullname, user_id
        public function createSignatory(string $inventory_unique_id, string $company_id, string $fullname, string $user_id) {
            $sql = "INSERT INTO inventory_signatory (inventory_unique_id, company_id, fullname, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $company_id, $fullname, $user_id]);
        }

        // function to check if inventory_unique_id and user_id already exist in inventory_signatory table
        public function checkSignatoryExist(string $inventory_unique_id, string $user_id) {
            $sql = "SELECT * FROM inventory_signatory WHERE inventory_unique_id = ? AND user_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $user_id]);
            $row = $stmt->fetch();
            if ($row) {
                # code...
                return true;
            } else {
                # code...
                return false;
            }
        }
        
        // function to add new data to stock table, requires inventory_unique_id, company_id, product, quantity
        public function createStock(string $inventory_unique_id, string $company_id, string $product, string $quantity) {
            $sql = "INSERT INTO stock (inventory_unique_id, company_id, product, quantity) VALUES (?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $company_id, $product, $quantity]);
        }

        // function to check if product already exist in stock
        public function checkProductExist(string $product) {
            $sql = "SELECT * FROM stock WHERE product = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$product]);
            $row = $stmt->fetch();
            if ($row) {
                # code...
                return true;
            } else {
                # code...
                return false;
            }
        }

        // function to input data to waybill table, requires  inventory_unique_id, company_id, product, quantity, details
        public function createWaybill(string $inventory_unique_id, string $company_id,string $product, string $quantity, string $details) {
            $sql = "INSERT INTO waybill (inventory_unique_id, company_id, product, quantity, details) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $company_id, $product, $quantity, $details]);
        }

        // function to edit waybill quanity and details where id is given
        public function editWaybill(string $id, string $quantity, string $details) {
            $sql = "UPDATE waybill SET quantity = ?, details = ? WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$quantity, $details, $id]);
        }

    }


?>