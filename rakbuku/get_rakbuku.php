<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_GET['rakbuku_id'])){
    $rakbuku_id = $_GET['rakbuku_id'];
    $stmt = $conn->prepare("SELECT * FROM users u LEFT JOIN rakbuku r ON u.id = r.user_id LEFT JOIN buku b ON r.buku_id = b.id WHERE r.id = ?");
    $stmt->bind_param("i", $rakbuku_id);
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
