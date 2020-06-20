<?php
class DB_Functions {
    private $conn;
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // koneksi ke database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
    // destructor
    function __destruct() {
         
    }
    public function simpanUser($username, $password, $notelp, $email, $nama_lengkap) {
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $stmt = $this->conn->prepare("INSERT INTO users(username, encrypted_password, salt, notelp, email, nama_lengkap) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $encrypted_password, $salt, $notelp, $email, $nama_lengkap);
        $result = $stmt->execute();
        $stmt->close();
        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }
    /**
     * Get user berdasarkan email dan password
     */
    public function getUserByUsernameAndPassword($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            // verifikasi password user
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // cek password jika sesuai
            if ($encrypted_password == $hash) {
                // autentikasi user berhasil
                return $user;
            }
        } else {
            return NULL;
        }
    }
    /**
     * Cek User ada atau tidak
     */
    public function isUserExisted($username) {
        $stmt = $this->conn->prepare("SELECT username from users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user telah ada 
            $stmt->close();
            return true;
        } else {
            // user belum ada 
            $stmt->close();
            return false;
        }
    }
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password.$salt, true).$salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password.$salt, true).$salt);
        return $hash;
    }
}
?>