<?php

    // declare strict mode
    declare(strict_types=1);

    // database connection
    abstract class DatabaseConnection {
        private $host = "localhost";
        private $user = "root";
        private $password = "";
        private $dbName = "komitex_stock";


        protected function connect() {
            $dsn = 'mysql:host='. $this->host .';dbname='. $this->dbName;
            $pdo = new PDO($dsn, $this->user, $this->password);
            // set how the data would be retrieved
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // data would be retrieved as an associated array

            return $pdo;
        }
    }

?>