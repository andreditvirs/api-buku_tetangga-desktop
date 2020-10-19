<?php
require_once '../../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array("error"=> false);

// Param terbaru bukan body GET
if (isset($_GET['kategori'])){
    $kategori = $_GET['kategori'];
    switch($kategori){
        case 'terbaru':
            $stmt = $conn->prepare("SELECT r.id, b.judul, b.pengarang, b.penerbit, r.harga, r.jumlah_stock, r.foto FROM rakbuku r JOIN buku b ON r.buku_id = b.id GROUP BY r.buku_id ORDER BY b.id DESC");
            $stmt->execute();
            $response['buku_terbaru']=array();
            if($result = $stmt->get_result()){
                while($row = $result->fetch_assoc()){
                    $row_result["rakbuku_id"] = $row["id"];
                    $row_result["judul"] = $row["judul"];
                    $row_result["pengarang"] = $row["pengarang"];
                    $row_result["penerbit"] = $row["penerbit"];
                    $row_result["harga"] = $row["harga"];
                    $row_result["jumlah_stock"] = $row["jumlah_stock"];
                    $row_result["foto"] = DB_LOC.DB_IMG.$row["foto"];
                    array_push($response['buku_terbaru'],$row_result);
                }
                $result->free();
            }
            $stmt->close();
            break;
        case 'terpopuler':
            $stmt = $conn->prepare("SELECT * FROM sewabuku s LEFT JOIN rakbuku r ON s.rakbuku_id = r.id LEFT JOIN buku b ON r.buku_id = b.id GROUP BY s.rakbuku_id ORDER BY COUNT(s.rakbuku_id) DESC");
            $stmt->execute();
            $response['buku_terpopuler']=array();
            if($result = $stmt->get_result()){
                while($row = $result->fetch_assoc()){
                    $row_result["rakbuku_id"] = $row["rakbuku_id"];
                    $row_result["judul"] = $row["judul"];
                    $row_result["pengarang"] = $row["pengarang"];
                    $row_result["penerbit"] = $row["penerbit"];
                    $row_result["harga"] = $row["harga"];
                    $row_result["jumlah_stock"] = $row["jumlah_stock"];
                    $row_result["foto"] = DB_LOC.DB_IMG.$row["foto"];
                    array_push($response['buku_terpopuler'],$row_result);
                }
                $result->free();
            }
            $stmt->close();
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
                        $row_result["judul"] = $row["judul"];
                        $row_result["pengarang"] = $row["pengarang"];
                        $row_result["penerbit"] = $row["penerbit"];
                        $row_result["harga"] = $row["harga"];
                        $row_result["jumlah_stock"] = $row["jumlah_stock"];
                        $row_result["foto"] = DB_LOC.DB_IMG.$row["foto"];
                        array_push($response['buku_rekomendasi'],$row_result);
                    }
                    $result->free();
                }
                $stmt->close(); 
            }else{
                $response["error"] = true;
                $response["error_msg"] = "Parameter tidak lengkap"; // Tidak ada username
            }
            break;
        case 'lain':
            if(isset($_POST['username'], $_POST['rakbuku_id'])){
                $username = $_POST['username'];
                $rakbuku_id = $_POST['rakbuku_id'];
                $stmt1 = $conn->prepare("SELECT id FROM users WHERE username = '$username'");
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $row1 = $result1->fetch_assoc();
                $id1 = $row1['id'];

                $stmt = $conn->prepare("SELECT r.id, u.username, r.harga, r.jumlah_stock, r.foto FROM rakbuku r JOIN users u ON r.user_id = '$id1' WHERE r.id <> '$rakbuku_id' GROUP BY r.id ORDER BY r.harga");
                $stmt->execute();
                $response['buku_lain']=array();
                if($result = $stmt->get_result()){
                    while($row = $result->fetch_assoc()){
                        $buku_temp['id'] = $row['id'];
                        $buku_temp['username'] = $row['username'];
                        $buku_temp['harga'] = $row['harga'];
                        $buku_temp['jumlah_stock'] = $row['jumlah_stock'];
                        $buku_temp['foto'] = DB_LOC.DB_IMG.$row['foto'];
                        array_push($response['buku_lain'],$row_result);
                    }
                    $result->free();
                } 
            }else{
                $response["error"] = true;
                $response["error_msg"] = "Parameter tidak lengkap";
            }
            $stmt1->close();
            break;   
    }
    echo json_encode($response);
}
?>