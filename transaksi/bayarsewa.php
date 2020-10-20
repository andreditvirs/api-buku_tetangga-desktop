<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
// Untuk PENYEDIA
if (isset($_POST['sewabuku_id']) && isset($_POST['kode_sewa'])){
    $sewabuku_id = $_POST['sewabuku_id'];
    $kode_sewa = $_POST['kode_sewa'];
    $stmt1 = $conn->prepare("SELECT status FROM sewabuku WHERE id = ? AND kode_sewa = ?");
    $stmt1->bind_param("ss", $sewabuku_id, $kode_sewa);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row = $result1->fetch_assoc();
    if(!is_null($row)){
        $status = $row["status"];
        $stmt1->close();

        // *NORMAL STEP* (Alur 0->1->2)
        // 0 untuk belum (belum dibayar, tapi sudah disewa atau dikurangkan stoknya dari tampilan sementara)
        // , 1 untuk sedang (jaminan dikurangi, uang sewa dibayar, bukunya dibawa stok dikurangi permanen)
        // , 2 untuk sudah (buku dikembalikan, jaminan dikembalikan)
        // *ABNORMAL STEP* (Alur 0->1->3->4)
        // , 3 untuk sedang (uang sewa dibayar setelah sudah meminjam/kesepakatan, ada extended day)
        // , 4 untuk sudah (uang dibayar, ada extended day, jaminan dikembalikan)
        $result2 = bayar($conn, $sewabuku_id, '0');
        if($result2){
            $response["error"] = false;
            $response["msg"] = "Data Pengembalian Peminjaman Buku Berhasil Diperbaharui";
        }else{
            $response["error"] = true; 
            $response["msg"] = "Konkesi ke Server Bermasalah";   
        }    
    }else{
        $response["error"] = true;
        $response["msg"] = "Kode Tidak Valid"; 
    }
    echo json_encode($response);
}

// Untuk PENYEWA (Tidak ada kode sewa)
if (isset($_POST['sewabuku_id']) && !isset($_POST['kode_sewa'])){
    $sewabuku_id = $_POST['sewabuku_id'];
    $stmt1 = $conn->prepare("SELECT status FROM sewabuku WHERE id = ?");
    $stmt1->bind_param("s", $sewabuku_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row = $result1->fetch_assoc();
    if(!is_null($row)){
        $stmt1->close();

        // *NORMAL STEP* (Alur 0->1->2)
        // 0 untuk belum (belum dibayar, tapi sudah disewa atau dikurangkan stoknya dari tampilan sementara)
        // , 1 untuk sedang (jaminan dikurangi, uang sewa dibayar, bukunya dibawa stok dikurangi permanen)
        // , 2 untuk sudah (buku dikembalikan, jaminan dikembalikan)
        // *ABNORMAL STEP* (Alur 0->1->3->4)
        // , 3 untuk sedang (uang sewa dibayar setelah sudah meminjam/kesepakatan, ada extended day)
        // , 4 untuk sudah (uang dibayar, ada extended day, jaminan dikembalikan)
        $kode_sewa= generateKode($conn);
        $result2 = insertKode($conn, $sewabuku_id, $kode_sewa);
        $data = array('kode_sewa' => $kode_sewa);
        if($result2){
            $response["data"] = $data;
            $response["error"] = false;
            $response["msg"] = "Data Pengembalian Peminjaman Buku Berhasil Diperbaharui";
        }else{
            $response["error"] = true; 
            $response["msg"] = "Kode Gagal Dibuat";   
        }    
    }else{
        $response["error"] = true;
        $response["msg"] = "Sewa Buku Tidak Ditemukan"; 
    }
    echo json_encode($response);
}

function generateKode($conn){
    $kode = mt_rand(1000, 9999);
    return $kode;
}

function insertKode($conn, $sewabuku_id, $kode_sewa){
    $stmt = $conn->prepare("UPDATE sewabuku SET status = '1', kode_sewa = ? WHERE id = ?");
    $stmt->bind_param("ss", $kode_sewa, $sewabuku_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function bayar($conn, $sewabuku_id, $kode_sewa){
    $stmt = $conn->prepare("UPDATE sewabuku SET status = '1', kode_sewa = ? WHERE id = ?");
    $stmt->bind_param("ss", $kode_sewa, $sewabuku_id);
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>
