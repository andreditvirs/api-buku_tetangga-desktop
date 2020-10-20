<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

// Untuk PENYEDIA (Tidak ada kode kembali)
$response = array();
if (isset($_POST['sewabuku_id']) && !isset($_POST['kode_kembali'])){
    $sewabuku_id = $_POST['sewabuku_id'];
    $stmt1 = $conn->prepare("SELECT status FROM sewabuku WHERE id = ?");
    $stmt1->bind_param("s", $sewabuku_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row = $result1->fetch_assoc();
    if(!is_null($row)){
        $status = $row["status"];
        $stmt1->close();

        $kode_kembali= generateKode($conn);
        $result2 = insertKode($conn, $sewabuku_id, $kode_kembali);
        $data = array('kode_kembali' => $kode_kembali);
        if($result2){
            $response["data"] = $data;
            $response["error"] = false;
            $response["msg"] = "Data Pengembalian Peminjaman Buku Berhasil Diperbaharui";
        }else{
            $response["error"] = true; 
            $response["msg"] = "Data Pengembalian Peminjaman Buku Gagal Diperbaharui";   
        }    
    }else{
        $response["error"] = true;
        $response["msg"] = "Sewa Buku Tidak Ditemukan"; 
    }
    echo json_encode($response);
}

// Untuk PENYEWA (Ada inputan kode kembali)
$response = array();
if (isset($_POST['sewabuku_id']) && isset($_POST['kode_kembali'])){
    $sewabuku_id = $_POST['sewabuku_id'];
    $kode_kembali = $_POST['kode_kembali'];
    $stmt1 = $conn->prepare("SELECT status FROM sewabuku WHERE id = ? AND kode_kembali = ?");
    $stmt1->bind_param("ss", $sewabuku_id, $kode_kembali);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row = $result1->fetch_assoc();
    if(!is_null($row)){
        $status = $row["status"];
        $stmt1->close();

        if($status == '1'){
            $status_baru = '2';
            $result2 = kembali($conn, $sewabuku_id, $status_baru, '0');
        }else if($status = '3'){
            $status_baru = '4';
            $result2 = kembali($conn, $sewabuku_id, $status_baru, '0');
        }else{
            //
        }
        if($result2){
            $response["error"] = false;
            $response["msg"] = "Data Pengembalian Peminjaman Buku Selesai";
        }else{
            $response["error"] = true; 
            $response["msg"] = "Data Pengembalian Peminjaman Buku Gagal Diperbaharui";   
        }    
    }else{
        $response["error"] = true;
        $response["msg"] = "Kode Tidak Valid"; 
    }
    echo json_encode($response);
}

function kembali($conn, $sewabuku_id, $status_baru, $kode_kembali){
    $stmt = $conn->prepare("UPDATE sewabuku SET status = ?, kode_kembali = ? WHERE id = ?");
    $stmt->bind_param("sss", $status_baru, $kode_kembali, $sewabuku_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function generateKode($conn){
    $kode = mt_rand(1000, 9999);
    return $kode;
}

function insertKode($conn, $sewabuku_id, $kode_kembali){
    $stmt = $conn->prepare("UPDATE sewabuku SET kode_kembali = ? WHERE id = ?");
    $stmt->bind_param("ss", $kode_kembali, $sewabuku_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>
