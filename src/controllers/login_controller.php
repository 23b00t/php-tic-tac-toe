<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];  
    $password = $_POST['password'];      

	loginUser($username, $password);
}

function loginUser($username, $password) {
	$servername = "127.0.0.1";
	$dbuser = "user_read";
	$dbpassword = "";  
	$dbname = "tic_tac_toe";

	// Verbindung herstellen
	$conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

	// Verbindung prüfen
	if ($conn->connect_error) {
		die("Verbindung fehlgeschlagen: " . $conn->connect_error . "\n");
	}

	$username = $conn->real_escape_string($username);

	// Passwort-Hash aus der Datenbank abrufen
	$sql = <<<SQL
		SELECT password 
		FROM user
		WHERE username = '$username';
	SQL;

	$result = $conn->query($sql);

	// Verbindung schließen
	$conn->close();

	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$hashed_password = $row['password'];

		// Überprüfen, ob das eingegebene Passwort mit dem gehashten Passwort übereinstimmt
		if (password_verify($password, $hashed_password)) {
			// Passwort stimmt überein, Login erfolgreich
			session_start();
			$_SESSION["login"] = "true";
			header('Location: /tic-tac-toe/app/src/views/options_view.php');
			exit();
		} else {
			// Passwort stimmt nicht überein
			header('Location: ../views/login_form.php?error=wrong%20credentials');
			exit();
		}
	} else {
		// Benutzername existiert nicht
			header('Location: ../views/login_form.php?error=wrong%20credentials');
		exit();
	}
}
?>
