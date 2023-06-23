<?php

require_once "../config.php";

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

$server = $server_name;
$user = $user_name;
$password = $db_password;
$db = $db_name;

class Database {
    private $server;
    private $user;
    private $password;
    private $db;

    function __construct() {
        global $server;
        global $user;
        global $password;
        global $db;
        
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->db = $db;
        // $this->server = "localhost:3306";
        // $this->user = "root";
        // $this->password = "root_miranda";
        // $this->db = "mirandahoteldb";
        // $this->server = getenv("MYSQL_SERVER");
        // $this->user = getenv("MYSQL_USER");
        // $this->password = getenv("MYSQL_DB_PASS");
        // $this->db = getenv("MYSQL_DB");
    }

    public function executeQuery($query) {
        $conn = new mysqli($this->server, $this->user, $this->password, $this->db);
        $result = $conn->query($query);
        $conn->close();
        return $result;
    }
    
}