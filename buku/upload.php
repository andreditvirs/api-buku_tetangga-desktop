<?php
define('host', 'localhost');
define('user', 'root');
define('pass', '');
define('db', 'shop');

$conn = mysqli_connect(host, user, pass, db) or die('Unable to Connect');

	$result = mysqli_query($conn, "SELECT * FROM productlist2");

	$response = array();
	$response['productList']= array();
	
	while ($row = mysqli_fetch_assoc($result)) {
	    array_push($response['productList'], array(
	        'productName' => $row['productName'], 
	        'productImageUrl' => $row['productImageUrl'], 
	        'region' => $row['region'],
	        'stock' => $row['stock'], ));
	}
	
	echo json_encode($response);
?>
