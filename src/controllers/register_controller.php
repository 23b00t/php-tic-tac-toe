<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];  
    $password = $_POST['password'];      
	$confirm_password = $_POST['confirm_password'];

	if ($password === $confirm_password) {
		createUser($username, $password);
	} else {
		header('Location: ../views/register_form.php?error=Passwörter%20stimmen%20nicht%20überein');
		exit();
	}
}

function createUser($username, $password) {
	$servername = "127.0.0.1";
	$dbuser = "user_write";
	$dbpassword = "";  
	$dbname = "tic_tac_toe";

	// Verbindung herstellen
	$conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

	// Verbindung prüfen
	if ($conn->connect_error) {
		die("Verbindung fehlgeschlagen: " . $conn->connect_error . "\n");
	}

	$username = $conn->real_escape_string($username);
	$password_hashed = password_hash($conn->real_escape_string($password), PASSWORD_DEFAULT); // Passwort hashen

	// Spaltennamen hinzufügen
	$sql = <<<SQL
		INSERT INTO user (username, password) VALUES (
			'$username', '$password_hashed'
		);
	SQL;

	try {
		$conn->query($sql);
		// Erfolgreiches Einfügen
		header('Location: ../../public/index.php');
		exit();
	} catch (mysqli_sql_exception $e) {
		if ($e->getCode() === 1062) {
			// Fehler 1062: Duplicate entry (Datenbankfehler für UNIQUE-Constraint)
			header('Location: ../views/register_form.php?error=Benutzername%20nicht%20mehr%20verfügbar');
		} else {
			// Andere Fehler
			header('Location: ../views/register_form.php?error=Unbekannter%20Fehler');
		}
		exit();
	}

	// Verbindung schließen
	$conn->close();
}
?>
