<?php

    // merchant model class should extend to DatabaseConnection class and App class

    class MerchantModel extends GeneralModel {
        
        // function to add data to inventory_signatory table, requires inventory_unique_id, company_id, fullname, user_id
        public function createSignatory(string $inventory_unique_id, string $inventory_name, string $company_id, string $fullname, string $user_id) {
            $sql = "INSERT INTO inventory_signatory (inventory_unique_id, inventory_name, company_id, fullname, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $inventory_name, $company_id, $fullname, $user_id]);
        }

        // function to check if inventory_unique_id and user_id already exist in inventory_signatory table
        public function checkSignatoryExist(string $inventory_unique_id, string $user_id) {
            $sql = "SELECT * FROM inventory_signatory WHERE inventory_unique_id = ? AND user_id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$inventory_unique_id, $user_id]);
            $row = $stmt->fetch();
            if ($row) return true;
            else return false;
        }
        
        // function to add new data to stock table, requires inventory_unique_id, company_id, product, quantity
        public function createStock(string $inventory_unique_id, string $company_id, string $product, string $quantity, string $product_image) {
            if ($product_image) {
                # code...
                $sql = "INSERT INTO stock (inventory_unique_id, company_id, product, quantity, product_image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->connect()->prepare($sql);
                $stmt->execute([$inventory_unique_id, $company_id, $product, $quantity, $product_image]);
            } else {
                # code...
                $sql = "INSERT INTO stock (inventory_unique_id, company_id, product, quantity) VALUES (?, ?, ?, ?)";
                $stmt = $this->connect()->prepare($sql);
                $stmt->execute([$inventory_unique_id, $company_id, $product, $quantity]);
            }
            
        }

        // function to check if product already exist in stock
        public function checkProductExist(string $product) {
            $sql = "SELECT * FROM stock WHERE product = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$product]);
            $row = $stmt->fetch();
            if ($row) return true;
            else return false;
        }

        // function to eit product in stock table, requires id
        public function setProductName(string $id, string $product_new_name) {
            $sql = "UPDATE stock SET product = ? WHERE id = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$product_new_name, $id]);
        }

        // function to insert data into product_name_change_history table, requires stock_id, product_new_name, product_old_name, user_id, fullname
        public function createProductNameChangeHistory(string $stock_id, string $product_new_name, string $product_old_name, string $user_id, string $changed_by, string $inventory_unique_id, string $inventory_name, string $company_id) {
            $sql = "INSERT INTO product_name_change_history (stock_id, product_new_name, product_old_name, user_id, changed_by, inventory_unique_id, inventory_name, company_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$stock_id, $product_new_name, $product_old_name, $user_id, $changed_by, $inventory_unique_id, $inventory_name, $company_id]);
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