<?php
require_once __DIR__ . '/../helpers/DatabaseHelper.php';

class Register {
	public static function createUser($username, $password) {
		$conn = DatabaseHelper::connect("user_write");

		$username = $conn->real_escape_string($username);
		$password_hashed = password_hash($password, PASSWORD_DEFAULT);

		// Spaltennamen hinzufügen
		$sql = <<<SQL
			INSERT INTO user (username, password) VALUES (
				'$username', '$password_hashed'
			);
		SQL;

		try {
			DatabaseHelper::query($conn, $sql);
			// Erfolgreiches Einfügen
			header('Location: ../../public/index.php');
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
