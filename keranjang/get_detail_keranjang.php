<?php

include 'fun_crud_keranjang.php';

function isPOSTParametersAvailable($params){
    $available = true;
    $missingparams = "";
    foreach($params as $param){
        if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
            $available = false;
            $missingparams = $missingparams . ", " . $param;
        }
    }
    if(!$available){
        return false;
    }else{
        return true;
    }
}

function isGETParametersAvailable($params){
    $available = true;
    $missingparams = "";
    foreach($params as $param){
        if(!isset($_GET[$param]) || strlen($_GET[$param])<=0){
            $available = false;
            $missingparams = $missingparams . ", " . $param;
        }
    }
    if(!$available){
        $response = array();
        $response['error'] = true;
        $response['message'] = 'Parameters ' . substr($missingparams, 1,
        strlen($missingparams)) . ' missing';
        
        echo json_encode($response);
        die();
    }
}

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

// Untuk PENYEWA memilih buku yang ingin disewa dan memasukkan ke keranjang
$response = array();
if(isset($_POST['penyewa_id']) && isset($_POST['rakbuku_id'])){
    isPOSTParametersAvailable(array('penyewa_id', 'rakbuku_id'));
    $result = addKeranjang($conn, $_POST['penyewa_id'], $_POST['rakbuku_id']);
    if($result){
        $response['error'] = false;
        $response['msg'] = 'Buku Sudah Masuk ke Keranjang';
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
    echo json_encode($response);
}

// Untuk PENYEWA mengetahui buku yang ada dikeranjang apa saja
if(isset($_GET['penyewa_id'])){
    isGETParametersAvailable(array('penyewa_id'));
    $result = cekKeranjang($conn, $_GET['penyewa_id']);
    if($result != null){
        $response['error'] = false;
        $response['msg'] = 'Keranjang Telah Dicari';
        $response['detail_keranjang'] = $result;
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
    echo json_encode($response);
}

// Untuk PENYEWA menghapus buku yang ada dikeranjang
if(isset($_POST['keranjang_id']) && !isset($_POST['jumlah_buku'])){
    isPOSTParametersAvailable(array('keranjang_id'));
    $result = deleteKeranjang($conn, $_POST['keranjang_id']);
    if($result != null){
        $response['error'] = false;
        $response['msg'] = 'Keranjang Telah Terhapus';
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
    echo json_encode($response);
}

// Untuk PENYEWA mengeset buku yang ada dikeranjang
if(isset($_POST['keranjang_id']) && isset($_POST['jumlah_buku'])){
    isPOSTParametersAvailable(array('keranjang_id, jumlah_buku'));
    $result = updateKeranjang($conn, $_POST['keranjang_id'], $_POST['jumlah_buku']);
    if($result != null){
        $response['error'] = false;
        $response['msg'] = 'Jumlah Buku Telah Diatur';
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
    echo json_encode($response);
}