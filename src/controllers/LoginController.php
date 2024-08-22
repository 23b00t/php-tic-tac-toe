<?php
require_once __DIR__ . '/../helpers/DatabaseHelper.php';

class Login {
	public static function loginUser($username, $password) {
		$conn = DatabaseHelper::connect("user_read");

		$username = $conn->real_escape_string($username);

		// Passwort-Hash aus der Datenbank abrufen
		$sql = <<<SQL
			SELECT password 
			FROM user
			WHERE username = '$username';
		SQL;

		$result = DatabaseHelper::query($conn, $sql);

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
				header('Location: views/login_form.php?error=Falsche%20Zugangsdaten');
				exit();
			}
		} else {
			// Benutzername existiert nicht
				header('Location: views/login_form.php?error=Falsche%20Zugangsdaten');
			exit();
		}
	}
}
?>
