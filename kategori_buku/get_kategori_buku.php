<?php
require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array("error"=> false);
if (isset($_GET['kategori'])){
    $kategori = $_GET['kategori'];
    switch($kategori){
        case 'terbaru':
            $stmt = $conn->prepare("SELECT r.id, b.judul_buku, b.pengarang, b.penerbit, r.harga, r.jumlah_stock, r.foto FROM rakbuku r JOIN buku b ON r.buku_id = b.id GROUP BY r.buku_id ORDER BY b.id DESC");
            $stmt->execute();
            $response['buku_terbaru']=array();
            if($result = $stmt->get_result()){
                while($row = $result->fetch_assoc()){
                    $row_result["rakbuku_id"] = $row["id"];
                    $row_result["judul_buku"] = $row["judul_buku"];
                    $row_result["pengarang"] = $row["pengarang"];
                    $row_result["penerbit"] = $row["penerbit"];
                    $row_result["harga"] = $row["harga"];
                    $row_result["jumlah_stock"] = $row["jumlah_stock"];
                    $row_result["foto"] = $row["foto"];
                    array_push($response['buku_terbaru'],$row_result);
                }
                $result->free();
            }
            break;
        case 'terpopuler':
            $stmt = $conn->prepare("SELECT * FROM sewabuku s LEFT JOIN rakbuku r ON s.rakbuku_id = r.id LEFT JOIN buku b ON r.buku_id = b.id GROUP BY s.rakbuku_id ORDER BY COUNT(s.rakbuku_id) DESC");
            $stmt->execute();
            $response['buku_terpopuler']=array();
            if($result = $stmt->get_result()){
                while($row = $result->fetch_assoc()){
                    $row_result["rakbuku_id"] = $row["rakbuku_id"];
                    $row_result["judul_buku"] = $row["judul_buku"];
                    $row_result["pengarang"] = $row["pengarang"];
                    $row_result["penerbit"] = $row["penerbit"];
                    $row_result["harga"] = $row["harga"];
                    $row_result["jumlah_stock"] = $row["jumlah_stock"];
                    $row_result["foto"] = $row["foto"];
                    array_push($response['buku_terpopuler'],$row_result);
                }
                $result->free();
            }
            break;
        case 'rekomendasi':
            if(isset($_GET['username'])){
                $username = $_GET['username'];
                $stmt = $conn->prepare("SELECT * FROM sewabuku s LEFT JOIN rakbuku r ON s.rakbuku_id = r.id LEFT JOIN buku b ON r.buku_id = b.id GROUP BY s.rakbuku_id ORDER BY r.harga");
                $stmt->execute();
                $response['buku_rekomendasi']=array();
                if($result = $stmt->get_result()){
                    while($row = $result->fetch_assoc()){
                        $row_result["rakbuku_id"] = $row["rakbuku_id"];
                        $row_result["judul_buku"] = $row["judul_buku"];
                        $row_result["pengarang"] = $row["pengarang"];
                        $row_result["penerbit"] = $row["penerbit"];
                        $row_result["harga"] = $row["harga"];
                        $row_result["jumlah_stock"] = $row["jumlah_stock"];
                        $row_result["foto"] = $row["foto"];
                        array_push($response['buku_rekomendasi'],$row_result);
                    }
                    $result->free();
                } 
            }else{
                $response["error"] = true;
                $response["error_msg"] = "Parameter tidak lengkap";
            }
            break;   
    }

    $stmt->close();
    echo json_encode($response);
}
?>