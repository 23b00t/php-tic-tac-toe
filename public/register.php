<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];  
    $password = $_POST['password'];      
	$confirm_password = $_POST['confirm_password'];

	if ($username && $password && $confirm_password) {
		header('location: index.php');
		exit();
	} else {
		header('location: register_form.php');
		exit();
	}
}
