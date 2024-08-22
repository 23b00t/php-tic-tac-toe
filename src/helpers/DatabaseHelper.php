<?php
class DatabaseHelper {
        public static function connect($dbuser, $dbpassword) {
                $servername = "127.0.0.1";
                $dbname = "tic_tac_toe";

                // Verbindung herstellen
                $conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

                // Verbindung prüfen
                if ($conn->connect_error) {
                    die("Verbindung fehlgeschlagen: " . $conn->connect_error . "\n");
                }

                return $conn;
        }

        public static function prepareAndExecute($conn, $sql, $params) {
                // Die SQL-Abfrage vorbereiten
                $stmt = $conn->prepare($sql);

                // Parameter an die vorbereitete Anweisung binden
                $stmt->bind_param(...$params);

                // Die vorbereitete Anweisung ausführen
                $stmt->execute();

                // Das Ergebnis abrufen
                $result = $stmt->get_result();

                // Ressourcen freigeben
                $stmt->close();
                $conn->close();

                return $result;
        }
}
?>
