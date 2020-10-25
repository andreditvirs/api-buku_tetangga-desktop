<?php

// CREATE
function addKeranjang($conn, $penyewa_id, $rakbuku_id) {
    $sql1 = "INSERT INTO keranjang(penyewa_id, rakbuku_id) VALUES('$penyewa_id', '$rakbuku_id')";
    if (mysqli_query($conn, $sql1)) {
        $sql2 = "SELECT id FROM keranjang WHERE penyewa_id = '$penyewa_id' AND rakbuku_id = '$rakbuku_id'";
	$result2 = mysqli_query($conn, $sql2);
    	$row2 = mysqli_fetch_array($result2);
	return $row2['id'];
    }
    mysqli_close($conn);
    return '';
}

// READ
function cekKeranjang($conn, $penyewa_id) {
    $sql1 = "SELECT id FROM keranjang WHERE penyewa_id = '$penyewa_id'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_array($result1);
    $array_all = array();
    if(!is_null($row1)){
        $sql2 = "SELECT *, r.foto AS rakbuku_foto, u.foto AS penyedia_foto
                , b.judul AS buku_judul, k.id AS keranjang_id FROM keranjang k LEFT JOIN rakbuku r ON k.rakbuku_id = r.id 
                LEFT JOIN users u ON k.penyewa_id = u.id
                LEFT JOIN buku b ON r.buku_id = b.id";
        $result2 = mysqli_query($conn, $sql2);
        while ($row = mysqli_fetch_array($result2)) {
            $array_temp = array();
            $array_temp['keranjang'] = array(
                                        'id' => $row['keranjang_id']
                                        );
            $array_temp['penyedia'] = array(
                                        'nama_lengkap' => $row['nama_lengkap']
                                        ,'foto' => $row['penyedia_foto']
                                        );
            $array_temp['buku'] = array(
                                        'judul' => $row['buku_judul']
                                        );
            $array_temp['rakbuku'] = array(
                                        'harga' => $row['harga']
                                        ,'foto' => DB_LOC.DB_IMG.$row['rakbuku_foto']
                                        );
            array_push($array_all, $array_temp);
        }
    }
    mysqli_close($conn);
    return $array_all;
}

// UPDATE
function updateKeranjang($conn, $keranjang_id, $jumlah_buku) {
    $sql = "UPDATE keranjang SET jumlah_buku='$jumlah_buku' WHERE id='$keranjang_id'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}

// DELETE
function deleteKeranjang($conn, $keranjang_id) {
    $sql = "DELETE FROM keranjang WHERE id ='$keranjang_id'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}