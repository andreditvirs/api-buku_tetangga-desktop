<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_POST['username']) && isset($_POST['rating'])){
    $username = $_POST['username'];
    $rating = $_POST['rating'];

    $stmt1 = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt1->bind_param("s", $username);
    $stmt1->execute();
    if($result1 = $stmt1->get_result()){
        $row = $result1->fetch_assoc();
        $penyewa_id = $row["id"];
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
    $stmt1->close();
    $stmt->close();
    echo json_encode($response);
}	
?>
