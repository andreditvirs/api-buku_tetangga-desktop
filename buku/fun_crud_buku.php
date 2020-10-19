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

function getBukuDalamRak($conn, $username, $rakbuku_id) {
    $sql1 = "SELECT id FROM users WHERE username = '$username'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_array($result1);
    $id1 = $row1['id'];
    $sql2 = "SELECT r.id, u.username, r.harga, r.jumlah_stock, r.foto FROM rakbuku r JOIN users u ON r.user_id = '$id1' WHERE r.id <> '$rakbuku_id' GROUP BY r.id ORDER BY r.harga";
    $result2 = mysqli_query($conn, $sql2);

    $buku = array();
    while ($row = mysqli_fetch_array($result2)) {
        $buku_temp = array();
        $buku_temp['id'] = $row['id'];
        $buku_temp['username'] = $row['username'];
        $buku_temp['harga'] = $row['harga'];
        $buku_temp['jumlah_stock'] = $row['jumlah_stock'];
        $buku_temp['foto'] = $row['foto'];
        array_push($buku, $buku_temp);
    }
    mysqli_close($conn);
    return $buku;
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

function getDetailBuku($conn, $rakbuku_id) {
    $sql = "SELECT *, r.foto AS foto FROM rakbuku r 
            JOIN users u ON r.user_id = u.id 
            JOIN buku b ON r.buku_id = b.id
            WHERE r.id = '$rakbuku_id'
            GROUP BY r.id ORDER BY r.harga";
    $result = mysqli_query($conn, $sql);

    $buku = array();
    while ($row = mysqli_fetch_array($result)) {
        $buku_temp = array();
        $buku_temp['penyedia'] = array(
                                    'username' => $row['username']
                                    ,'notelp' => $row['notelp']
                                    ,'alamat' => $row['alamat']
                                );
        $buku_temp['buku'] = array(
                                    'isbn' => $row['isbn']
                                    ,'judul' => $row['judul']
                                    ,'pengarang' => $row['pengarang']
                                    ,'penerbit' => $row['penerbit']
                                    ,'kategori' => $row['kategori']
                                    ,'deskripsi' => $row['deskripsi']
                                    );
        $buku_temp['rakbuku'] = array(
                                    'harga' => $row['harga']
                                    ,'jumlah_stock' => $row['jumlah_stock']
                                    ,'keterangan' => $row['keterangan']
                                    ,'foto' => DB_LOC.DB_IMG.$row['foto']
                                    );
        array_push($buku, $buku_temp);
    }
    mysqli_close($conn);
    return $buku;
}
