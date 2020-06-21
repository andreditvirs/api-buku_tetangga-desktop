<?php
require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array("error"=> true);

if(isset($_GET["username"])){
    $username = $_GET["username"];
    $stmt = $conn->prepare("SELECT status FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $response = array();
    if($result = $stmt->get_result()){
        $row = $result->fetch_assoc();
        $response["error"] = false;
        $response["status"] = $row["status"];
        $result->free();
        $stmt->close();               
    }else{
        $response["error"] = true;
        $response["error_msg"] = "API Invalid";
    }
}else{
    $response["error"] = true;
    $response["error_msg"] = "Paramater ada yang kurang";    
}
echo json_encode($response);
?>