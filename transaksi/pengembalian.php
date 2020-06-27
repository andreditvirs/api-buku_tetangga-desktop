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
    $result1 = $stmt1->execute();
    $stmt1->close();
    if($result1){
        $penyewa_id = $result1["penyewa_id"]; 
        $stmt = $conn->prepare("UPDATE sewabuku SET status = 'Belum' WHERE rakbuku_id = ? AND penyewa_id = ?");
        $stmt->bind_param("ss", $penyewa_id, $rakbuku_id);
        $result = $stmt->execute();
        if($result){
            $response["error"] = false;
        }else{
            $response["error"] = true;    
        }    
    }else{
        $response["error"] = true;
    }
    $stmt->close();
    echo json_encode($response);
}	
?>
