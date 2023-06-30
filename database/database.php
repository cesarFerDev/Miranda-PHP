<?php

require_once "../config.php";

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
    }

    public function executeQuery($query) {
        $conn = new mysqli($this->server, $this->user, $this->password, $this->db);
        $result = $conn->query($query);
        $conn->close();
        return $result;
    }
    
}