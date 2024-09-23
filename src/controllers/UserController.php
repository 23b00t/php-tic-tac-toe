<?php
require_once __DIR__ . '/../helpers/DatabaseHelper.php';

class UserController
{
    public static function loginUser($username, $password)
    {
        // Datenbank verbindung mit dem Nutzer user_read herstellen
        $db = new DatabaseHelper("user_read", "password");

        // Die SQL-Abfrage und die Parameter definieren
        $sql = 'SELECT password FROM user WHERE username = ?';
        $params = ['s', $username];  // 's' steht für den Typ (String)

        // Die vorbereitete Anweisung ausführen
        $result = $db->prepareAndExecute($sql, $params);

        // prüfen, ob der Benutzer existiert
        if ($result && $result->num_rows > 0) {
            // gehastes Passwort abrufen
            // #fetch_assoc: Liefert einen Datensatz als assoziatives Array
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

    public static function createUser($username, $password)
    {
        // Datenbank verbindung mit dem Nutzer user_write herstellen
        $db = new DatabaseHelper("user_write", "password_write");

        // Passwort mit Standardeinstellungen hashen
        $password_hashed = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);

        // SQL-Abfrage und Parameter definieren
        $sql = 'INSERT INTO user (username, password) VALUES (?, ?)';
        $params = ['ss', $username, $password_hashed];  // 'ss' steht für zwei Strings

        // Versuchen den Benutzer anzulegen. Wenn Fehler auftritt, z. B. Verstoß gegen
        // UNIQUE-Constraint catch Block ausführen
        try {
            $db->prepareAndExecute($sql, $params);
            // Erfolgreiches Einfügen
            header('Location: views/login_form.php?msg=Account%20erfolgreich%20erstellt');
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
