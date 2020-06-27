<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_POST['rakbuku_id']) && isset($_POST['penyewa'])){
    $rakbuku_id = $_POST['rakbuku_id'];
    $penyewa = $_POST['penyewa'];
    $stmt1 = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt1->bind_param("s", $penyewa);
    $stmt1->execute(); 
    if($result1 = $stmt1->get_result()){
        $row = $result1->fetch_assoc();
        $penyewa_id = $row["id"];
        $status = "Sedang";
        date_default_timezone_set("Asia/Jakarta");
        $tanggal_sewa = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO sewabuku(penyewa_id, rakbuku_id, tanggal_sewa, status) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $penyewa_id, $rakbuku_id, $tanggal_sewa, $status);
        $result = $stmt->execute();
        if($result){
            $response["error"] = false;
        }else{
            $response["error"] = true;    
        }    
    }else{
        $response["error"] = true;
    }
    $stmt1->close();
    $stmt->close();
    echo json_encode($response);
}	
?>
