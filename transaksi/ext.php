<?php

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if (isset($_POST['sewabuku_id']) && isset($_POST['ext_day'])){
    $sewabuku_id = $_POST['sewabuku_id'];
    $ext_day = $_POST['ext_day'];
    $stmt1 = $conn->prepare("SELECT id, tanggal_kembali FROM sewabuku WHERE id = ?");
    $stmt1->bind_param("s", $sewabuku_id);
    $stmt1->execute();
    if($result1 = $stmt1->get_result()){
        $row = $result1->fetch_assoc();
        $tanggal_kembali = $row["tanggal_kembali"];
        $stmt1->close();

        // Tambah ke tanggal kembali sebelumnya
        $tanggal_kembali_ext = date('Y-m-d', strtotime($tanggal_kembali.' + '.$ext_day.' days'));
        // *NORMAL STEP* (Alur 0->1->2)
        // 0 untuk belum (belum dibayar, tapi sudah disewa atau dikurangkan stoknya dari tampilan sementara)
        // , 1 untuk sedang (jaminan dikurangi, uang sewa dibayar, bukunya dibawa stok dikurangi permanen)
        // , 2 untuk sudah (buku dikembalikan, jaminan dikembalikan)
        // *ABNORMAL STEP* (Alur 0->1->3->4)
        // , 3 untuk sedang (uang sewa dibayar setelah sudah meminjam/kesepakatan, ada extended day)
        // , 4 untuk sudah (uang dibayar, ada extended day, jaminan dikembalikan)
        $stmt3 = $conn->prepare("UPDATE sewabuku SET status = '3', tanggal_kembali = ?, ext_day = ? WHERE id = ?");
        $stmt3->bind_param("sss", $tanggal_kembali_ext, $ext_day, $sewabuku_id);
        $result3 = $stmt3->execute();
        $stmt3->close();
        if($result3){
            $response["error"] = false;
            $response["msg"] = "Tanggal Pengembalian Peminjaman Buku Berhasil Diubah Menjadi ".$tanggal_kembali_ext;
        }else{
            $response["error"] = true; 
            $response["msg"] = "Tanggal Pengembalian Peminjaman Buku Gagal Diubah";   
        }    
    }else{
        $response["error"] = true;
        $response["msg"] = "Konkesi ke Server Bermasalah"; 
    }
    echo json_encode($response);
}	
?>
