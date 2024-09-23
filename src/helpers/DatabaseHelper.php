<?php
class DatabaseHelper {
    private $conn;

    public function __construct($dbuser, $dbpassword) {
        $servername = "127.0.0.1";
        $dbname = "tic_tac_toe";

        // Verbindung herstellen
        $this->conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

        // Verbindung prüfen
        if ($this->conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $this->conn->connect_error . "\n");
        }
    }

    public function prepareAndExecute($sql, $params) {
        // Die SQL-Abfrage vorbereiten
        $stmt = $this->conn->prepare($sql);

        // Parameter an die vorbereitete Anweisung binden
        $stmt->bind_param(...$params);

        // Die vorbereitete Anweisung ausführen
        $stmt->execute();

        // Das Ergebnis abrufen
        $result = $stmt->get_result();

        // Ressourcen freigeben
        $stmt->close();

        return $result;
    }

    public function __destruct() {
        // Verbindung zur Datenbank schließen
        if ($this->conn) {
            $this->conn->close();
            echo "Datenbankverbindung geschlossen.\n";
        }
    }
}
