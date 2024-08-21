<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];  
    $password = $_POST['password'];      

	if ($username && $password) {
		header('location: options.php');
		exit();
	} else {
		header('location: index.php');
		exit();
	}
}
