<?php
require_once __DIR__ . '/../helpers/DatabaseHelper.php';

class Register {
	public static function createUser($username, $password) {
		// Datenbank verbindung mit dem Nutzer user_write herstellen
		$conn = DatabaseHelper::connect("user_write", "password_write");

		// Passwort mit Standardeinstellungen hashen
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // SQL-Abfrage und Parameter definieren
        $sql = 'INSERT INTO user (username, password) VALUES (?, ?)';
        $params = ['ss', $username, $password_hashed];  // 'ss' steht für zwei Strings

        // Versuchen den Benutzer anzulegen. Wenn Fehler auftritt, z. B. Verstoß gegen
        // UNIQUE-Constraint catch Block ausführen
        try {
            DatabaseHelper::prepareAndExecute($conn, $sql, $params);
			// Erfolgreiches Einfügen
			header('Location: ../public/index.php');
			exit();
		} catch (mysqli_sql_exception $e) {
			if ($e->getCode() === 1062) {
				// Fehler 1062: Duplicate entry (Datenbankfehler für UNIQUE-Constraint)
				header('Location: views/register_form.php?error=Benutzername%20nicht%20mehr%20verfügbar');
			} else {
				// Andere Fehler
				header('Location: views/register_form.php?error=Unbekannter%20Fehler');
			}
			exit();
		}
	}
}
?>
