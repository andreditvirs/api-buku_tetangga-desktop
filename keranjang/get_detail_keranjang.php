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
if(isPOSTParametersAvailable(array('penyewa_id', 'rakbuku_id'))){
    $result = addKeranjang($conn, $_POST['penyewa_id'], $_POST['rakbuku_id']);
    if($result){
        $response['error'] = false;
        $response['msg'] = 'Buku Sudah Masuk ke Keranjang';
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
    echo json_encode($response);
}else{
    $response['error'] = true;
    $response['msg'] = 'Invalid API Call';
    echo json_encode($response);
}

// Untuk PENYEWA mengetahui buku yang ada dikeranjang apa saja
if(isGETParametersAvailable(array('penyewa_id'))){
    $result = cekKeranjang($conn, $_POST['penyewa_id']);
    if($result != null){
        $response['error'] = false;
        $response['msg'] = 'Buku Telah Dicari';
        $response['detail_keranjang'] = $result;
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
    echo json_encode($response);
}else{
    $response['error'] = true;
    $response['msg'] = 'Invalid API Call';
    echo json_encode($response);
}