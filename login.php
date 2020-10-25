<?php
require_once 'include/DB_Function.php';
$db = new DB_Functions();
// json response array
$response = array("error" => FALSE);
if (isset($_POST['username']) && isset($_POST['password'])) {
    // menerima parameter POST ( username dan password )
    $username = $_POST['username'];
    $userpass = $_POST['password'];
    // get the user by username and password
    // get user berdasarkan username dan password
    $user = $db->getUserByUsernameAndPassword($username, $userpass);
    if ($user != false) {
        // user ditemukan
        $response["error"] = FALSE;
        $response["user"]["id"] = $user["id"];
	$response["user"]["nama_lengkap"] = $user["nama_lengkap"];
        echo json_encode($response);
    } else {
        // user tidak ditemukan password/email salah
        $response["error"] = TRUE;
        $response["error_msg"] = "Login gagal. Password/Email salah";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter (email atau password) ada yang kurang";
    echo json_encode($response);
}
?>