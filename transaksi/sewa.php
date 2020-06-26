<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_POST['rakbuku_id']) && isset($_POST['penyewa'])){
    $rakbuku_id = $_POST['rakbuku_id'];
    $penyewa = $_POST['penyewa'];
    $stmt = $conn->prepare("INSERT INTO sewabuku(penyewa_id, rakbuku_id) VALUES(?, ?)");
    $stmt->bind_param("ss", $penyewa_id, $rakbuku_id);
    $result = $stmt->execute();
    if($result){
        $response["error"] = false;
    }else{
        $response["error"] = true;
    }
    $stmt->close();
    echo json_encode($response);
}	
?>
