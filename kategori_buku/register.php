<?php
	require "init.php";
	$name = $_GET["name"];
	$user_name = $_GET["user_name"];
	$user_password = $_GET["user_password"];

	$sql = "SELECT * FROM login_info WHERE user_name = '$user_name'";

	$result = mysqli_query($con, $sql);

	if(mysqli_num_rows($result)>0){
		$status = "exist";
	}else{
		$sql = "INSERT INTO login_info(name, user_name, user_password) VALUES('$name', '$user_name', '$user_password');";
		if(mysqli_query($con, $sql)){
			$status = "ok";
		}else{
			$status = "error";
		}
	}

	echo json_encode(array("response"=>$status));

	mysqli_close($con);
?>