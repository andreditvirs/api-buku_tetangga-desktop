<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "clientserver";
    $conn = mysqli_connect($hostname, $username, $password, $dbname);
    
    if (!$conn) {
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
