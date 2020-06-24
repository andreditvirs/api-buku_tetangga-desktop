<?php
define("DB_LOC", "http://192.168.43.147/buku_tetangga/");
define("DB_IMG", "image/buku/");
class DB_Connect {
    private $conn;
    // koneksi ke database
    public function connect() {
        require_once 'Config.php';
        
        // koneksi ke mysql database
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
         
        // return database handler
        return $this->conn;
    }
}
?>
