<?php
class DatabaseHelper {
	public static function connect($dbuser) {
		$servername = "127.0.0.1";
		$dbpassword = "";  
		$dbname = "tic_tac_toe";

		// Verbindung herstellen
		$conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

		// Verbindung prüfen
		if ($conn->connect_error) {
			die("Verbindung fehlgeschlagen: " . $conn->connect_error . "\n");
		}

		return $conn;
	}

	public static function query($conn, $sql) {
		$result = $conn->query($sql);

		// Verbindung schließen
		$conn->close();

		return $result;
	}
}
?>
