<?php
require_once __DIR__ . '/../helpers/DatabaseHelper.php';

class Login {
	public static function loginUser($username, $password) {
		// Datenbank verbindung mit dem Nutzer user_read herstellen
		$conn = DatabaseHelper::connect("user_read", "password");

        // Die SQL-Abfrage und die Parameter definieren
        $sql = 'SELECT password FROM user WHERE username = ?';
        $params = ['s', $username];  // 's' steht für den Typ (String)

        // Die vorbereitete Anweisung ausführen
        $result = DatabaseHelper::prepareAndExecute($conn, $sql, $params);

		// prüfen, ob der Benutzer existiert
		if ($result && $result->num_rows > 0) {
			// gehastes Passwort abrufen
			$row = $result->fetch_assoc();
			$hashed_password = $row['password'];

			// Überprüfen, ob das eingegebene Passwort mit dem gehashten Passwort übereinstimmt
			if (password_verify($password, $hashed_password)) {
				// Passwort stimmt überein, Login erfolgreich
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
