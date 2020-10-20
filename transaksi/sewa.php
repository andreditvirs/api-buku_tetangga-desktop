<?php

// KETERANGAN : FILE SEWA pada saat kondisi booking belum membayar apapun

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

// Untuk PENYEWA
$response = array();
if (isset($_POST['rakbuku_id']) && isset($_POST['penyewa_id']) && isset($_POST['lama_sewa'])){
    $rakbuku_id = $_POST['rakbuku_id'];
    $penyewa_id = $_POST['penyewa_id'];
    $lama_sewa = $_POST['lama_sewa'];
    $stmt1 = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt1->bind_param("s", $penyewa_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row = $result1->fetch_assoc();
    if(!is_null($row)){
        $penyewa_id = $row["id"];
        $stmt1->close();
        date_default_timezone_set("Asia/Jakarta");
        $tanggal_sewa = date('Y-m-d');

        // *NORMAL STEP* (Alur 0->1->2)
        // 0 untuk belum (belum dibayar, tapi sudah disewa atau dikurangkan stoknya dari tampilan sementara)
        // , 1 untuk sedang (jaminan dikurangi, uang sewa dibayar, bukunya dibawa stok dikurangi permanen)
        // , 2 untuk sudah (buku dikembalikan, jaminan dikembalikan)
        // *ABNORMAL STEP* (Alur 0->1->3->4)
        // , 3 untuk sedang (uang sewa dibayar setelah sudah meminjam/kesepakatan, ada extended day)
        // , 4 untuk sudah (uang dibayar, ada extended day, jaminan dikembalikan)
        $tanggal_kembali = date('Y-m-d', strtotime($tanggal_sewa.' + '.$lama_sewa.' days'));
        $stmt = $conn->prepare("INSERT INTO sewabuku(penyewa_id, rakbuku_id, tanggal_sewa, tanggal_kembali) VALUES(?, ?, ?, ?)");    
        $stmt->bind_param("ssss", $penyewa_id, $rakbuku_id, $tanggal_sewa, $tanggal_kembali);
        
        $result = $stmt->execute();
        if($result){
            $response["error"] = false;
	        $response["msg"] = "Peminjaman Berhasil Dilakukan";
        }else{
            $response["error"] = true; 
	        $response["msg"] = "Peminjaman Gagal";   
        }    
    }else{
        $response["error"] = true;
	    $response["msg"] = "Konkesi ke Server Bermasalah"; 
    }
    $stmt->close();
    echo json_encode($response);
}	
?>
