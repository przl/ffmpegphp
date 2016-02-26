<?php 

if ($_FILES['video_file']) {
	$resolution = $_POST['resolution'];
	$email = $_POST['email'];
} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	echo 'Please upload a file...';
	return;
}




var_dump($resolution);
var_dump($email);


var_dump($_POST);