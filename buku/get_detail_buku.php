<?php

include 'fun_crud_buku.php';

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

require_once '../include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();
if(isPOSTParametersAvailable(array('rakbuku_id'))){
    $result = getDetailBuku($conn, $_POST['rakbuku_id']);
    if($result){
        $response['error']=false;
        $response['msg'] = 'Buku berhasil dicari';
        $response['detail_buku'] = $result;
    }else{
        $response['error'] = true;
        $response['msg'] = 'Some error';
    }
}else{
    $response['error'] = true;
    $response['msg'] = 'Invalid API Call';
}
echo json_encode($response);
