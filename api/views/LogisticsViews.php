<?php

    // logistics view extends logistics model

    class LogisticsView extends LogisticsModel{

        // function to check for inventory where company_id is given
        public function checkInventory(string $company_id){
            $results = $this->checkForInventory($company_id);
            // if results exist, return true else return false
            if ($results) return true;
            else return false;
        }
    }

?>