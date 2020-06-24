<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_POST['rakbuku_id'])){
    $rakbuku_id = $_POST['rakbuku_id'];
    $stmt = $conn->prepare("SELECT * FROM users u LEFT JOIN rakbuku r ON u.id = r.user_id LEFT JOIN buku b ON r.buku_id = b.id WHERE r.id = ?");
    $stmt->bind_param("s", $rakbuku_id);
    $stmt->execute();

    if($result = $stmt->get_result()){
        $row = $result->fetch_assoc();
        if($row){
            $row_result["username"] = $row["username"];
            $row_result["deskripsi"] = $row["deskripsi"];
            $row_result["judul_buku"] = $row["judul_buku"];
            $row_result["pengarang"] = $row["pengarang"];
            $row_result["penerbit"] = $row["penerbit"];
            $row_result["kategori"] = $row["kategori"];
            $row_result["harga"] = $row["harga"];
            $row_result["bahasa"] = $row["bahasa"];
            $row_result["berat"] = $row["berat"];
            $row_result["panjang"] = $row["panjang"];
            $row_result["lebar"] = $row["lebar"];
            $row_result["jumlah_stock"] = $row["jumlah_stock"];
            $row_result["foto"] = DB_LOC.DB_IMG.$row["foto"];
            $response= $row_result;
            $result->free();
        }else{
            $response["error"] = true;
        }
    }

    $stmt->close();
    echo json_encode($response);
}	
?>
