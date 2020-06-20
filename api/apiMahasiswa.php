<?php

include '../model/mahasiswa.php';
include '../config/config.php';

function isTheseParametersAvailable($params){
    $available = true;
    $missingparams = "";
    foreach($params as $param){
        if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
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

require_once './include/DB_Connect.php';
// koneksi ke database
$db = new Db_Connect();
$conn = $db->connect();

$response = array();

if(isset($_GET['apicall'])){
    switch($_GET['apicall']){
        case 'c_buku':
            isTheseParametersAvailable(array('isbn','judul_buku', 'jumlah_stock', 'harga', 'foto', 'pengarang', 'penerbit', 'deskripsi', 'bahasa', 'berat', 'panjang', 'lebar'));
            $result=createMahasiswa($conn,$_POST['nama'],$_POST['alamat']);
            if($result){
                $response['error']=false;
                $response['message'] = 'Buku berhasil ditambahkan';
                $response['mahasiswa'] = getMahasiswa($conn);
            }else{
                $response['error'] = true;
                $response['message'] = 'Some error';
            }
            break;
        case 'u_buku':
            isTheseParametersAvailable(array('id','nama','alamat'));
            $result=updateMahasiswa($conn,$_POST['id'],$_POST['nama'],
            $_POST['alamat']);
            if($result){
                $response['error']=false;
                $response['message'] = 'Mahasiswa berhasil ditambahkan';
                $response['mahasiswa'] = getMahasiswa($conn);
            }else{
                $response['error'] = true;
                $response['message'] = 'Some error';
            }
            break;
        case 'g_buku':
            $response['error'] = false;
            $response['message'] = 'Request successfully completed';
            $response['mahasiswa'] = getMahasiswa($conn);
            break;
        case 'd_buku':
            if(isset($_GET['id'])){
                if(deleteMahasiswa($conn,$_GET['id'])){
                    $response['error']=false;
                    $response['message'] = 'Delete mahasiswa berhasil';
                    $response['mahasiswa'] = getMahasiswa($conn);
                }else{
                    $response['error'] = true;
                    $response['message'] = 'Some error';
                }
            }else{
                $response['error'] = true;
                $response['message'] = 'Nothing to delete';
            }
            break;
    }
}
else{
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}
echo json_encode($response);
