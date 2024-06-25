<?php

namespace Classes;

use mysqli;

class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'recipeapp';
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>
