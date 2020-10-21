<?php

function addKeranjang($conn, $penyewa_id, $rakbuku_id) {
    $sql = "INSERT INTO keranjang(penyewa_id, rakbuku_id) VALUES('$penyewa_id', '$rakbuku_id')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    mysqli_close($conn);
    return false;
}

function cekKeranjang($conn, $penyewa_id) {
    $sql1 = "SELECT id FROM keranjang WHERE penyewa_id = '$penyewa_id'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_array($result1);
    $keranjang = array();
    if(!is_null($row1)){
        $sql2 = "SELECT * FROM keranjang k LEFT JOIN users u ON u.id = k.penyewa_id 
                LEFT JOIN rakbukuWHERE penyewa_id = '$penyewa_id'";
        $result2 = mysqli_query($conn, $sql2);
        while ($row = mysqli_fetch_array($result2)) {
            $buku_temp = array();
            $buku_temp['id'] = $row['id'];
            $buku_temp['username'] = $row['username'];
            $buku_temp['harga'] = $row['harga'];
            $buku_temp['jumlah_stock'] = $row['jumlah_stock'];
            $buku_temp['foto'] = $row['foto'];
            array_push($buku, $buku_temp);
        }
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
    $sql = "SELECT *, r.id AS rakbuku_id, r.foto AS foto FROM rakbuku r 
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
				    'id' => $row['rakbuku_id']
                                    ,'harga' => $row['harga']
                                    ,'jumlah_stock' => $row['jumlah_stock']
                                    ,'keterangan' => $row['keterangan']
                                    ,'foto' => DB_LOC.DB_IMG.$row['foto']
                                    );
        array_push($buku, $buku_temp);
    }
    mysqli_close($conn);
    return $buku;
}
