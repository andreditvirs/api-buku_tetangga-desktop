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
        $response = array();
        $response['error'] = true;
        $response['message'] = 'Parameters ' . substr($missingparams, 1,
        strlen($missingparams)) . ' missing';
        
        echo json_encode($response);
        die();
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

$response = array();

if(isset($_GET['apicrud'])){
    switch($_GET['apicrud']){
        case 'req_buku':
            isPOSTParametersAvailable(array('isbn','judul_buku', 'jumlah_stock', 'harga', 'foto', 'pengarang', 'penerbit', 'deskripsi', 'bahasa', 'berat', 'panjang', 'lebar'));
            $result=createMahasiswa($conn, $_POST['nama'], $_POST['alamat']);
            if($result){
                $response['error']=false;
                $response['message'] = 'Buku berhasil ditambahkan';
                $response['mahasiswa'] = getMahasiswa($conn);
            }else{
                $response['error'] = true;
                $response['message'] = 'Some error';
            }
            break;
        case 'a_buku' :
            isPOSTParametersAvailable(array('username', 'isbn', 'harga', 'jumlah_stock', 'keterangan'));
            $result = addBuku($conn, $_POST['username'], $_POST['isbn'], $_POST['harga'], $_POST['jumlah_stock'], $_POST['keterangan']);
            if($result){
                $response['error']=false;
                $response['message'] = 'Buku berhasil ditambahkan';
            }else{
                $response['error'] = true;
                $response['message'] = 'Some error';
            }
            break;
        case 'u_buku':
            isPOSTParametersAvailable(array('id','nama','alamat'));
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
