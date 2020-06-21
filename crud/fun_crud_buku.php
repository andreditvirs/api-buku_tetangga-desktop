<?php

function createMahasiswa($conn, $nama, $alamat) {
    $sql = "INSERT INTO profile(nama,alamat) VALUES('$nama','$alamat')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}

function addBuku($conn, $username, $isbn, $harga, $jumlah_stock, $keterangan){
    $sql1 = "SELECT id FROM users WHERE username = '$username'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_array($result1);
    $id1 = $row1['id'];
    $sql2 = "SELECT id FROM buku WHERE isbn = '$isbn'";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_array($result2);
    $id2 = $row2['id'];
    $sql3 = "INSERT INTO rakbuku(user_id, buku_id, harga, jumlah_stock, keterangan) VALUES('$id1', '$id2', '$harga', '$jumlah_stock', '$keterangan')";
    print($sql3);
    if (mysqli_query($conn, $sql3)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}

function getMahasiswa($conn) {
    $sql = "SELECT * FROM profile";
    $result = mysqli_query($conn, $sql);

    $mahasiswa = array();
    while ($row = mysqli_fetch_array($result)) {
        $mahasiswa_temp = array();
        $mahasiswa_temp['id'] = $row['id'];
        $mahasiswa_temp['nama'] = $row['nama'];
        $mahasiswa_temp['alamat'] = $row['alamat'];
        array_push($mahasiswa, $mahasiswa_temp);
    }
    mysqli_close($conn);
    return $mahasiswa;
}

function updateMahasiswa($conn, $id, $nama, $alamat) {
    $sql = "UPDATE profile SET nama='$nama', alamat='$alamat' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}

function deleteMahasiswa($conn, $id) {
    $sql = "DELETE FROM profile WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}
