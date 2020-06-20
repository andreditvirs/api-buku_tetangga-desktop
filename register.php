<?php
require_once 'include/DB_Function.php';
$db = new DB_Functions();
// json response array
$response = array("error" => FALSE);
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['notelp']) && isset($_POST['email']) && isset($_POST['nama_lengkap'])) {
    // menerima parameter POST
    $username = $_POST['username'];
    $password = $_POST['password'];
    $notelp = $_POST['notelp'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    // Cek jika user ada dengan username sama
    if ($db->isUserExisted($username)) {
        // user telah ada
        $response["error"] = TRUE;
        $response["error_msg"] = "User telah ada dengan usernama " . $username;
        echo json_encode($response);
    } else {
        // buat user baru
        $user = $db->simpanUser($username, $password, $notelp, $email, $nama_lengkap);
        if ($user) {
            // simpan user berhasil
            $response["error"] = FALSE;
            echo json_encode($response);
        } else {
            // gagal menyimpan user
            $response["error"] = TRUE;
            $response["error_msg"] = "Terjadi kesalahan saat melakukan registrasi";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter (nama, email, atau password) ada yang kurang";
    echo json_encode($response);
}
?>