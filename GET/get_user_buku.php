<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_GET['username'])){
    $username = $_GET['username'];
    $stmt = $conn->prepare("SELECT * FROM users u LEFT JOIN rakbuku r ON u.id = r.user_id LEFT JOIN buku b ON r.buku_id = b.id WHERE u.username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    if($result = $stmt->get_result()){
        while($row = $result->fetch_assoc()){
            $row_result["judul_buku"] = $row["judul_buku"];
            $row_result["pengarang"] = $row["pengarang"];
            $row_result["penerbit"] = $row["penerbit"];
            $row_result["harga"] = $row["harga"];
            $row_result["jumlah_stock"] = $row["jumlah_stock"];
            $row_result["foto"] = $row["foto"];
            array_push($response,$row_result);
        }
        $result->free();
    }

    $stmt->close();
    echo json_encode($response);
}	
?>
