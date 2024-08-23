<?php
// INFO: Router

// Überprüfe, ob keine Session aktiv ist, dann starte eine  
session_status() === PHP_SESSION_NONE && session_start();

// Binde Dateien ein
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/src/game/HumanMove.php');
require_once(__ROOT__.'/src/helpers/Helper.php');
require_once(__ROOT__.'/src/game/Board.php');
require_once(__ROOT__.'/src/game/ComputerMove.php');
require_once(__ROOT__.'/src/controllers/GameController.php');
require_once(__ROOT__.'/src/controllers/UserController.php');

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
$_SERVER["REQUEST_METHOD"] === "POST" && init("POST");

// HACK: Simuliere POST und GET per übergabe als String Argument an run
// --> Lösung für Züge durch Computergegener

function init($method) {
    // Führe Login durch
    if (isset($_POST['login'])) {
        Helper::checkCSRFToken();
        $username = $_POST['username'];  
        $password = $_POST['password'];      

        UserController::loginUser($username, $password);
    }

    // Führe Registrierung durch
    if (isset($_POST['register'])) {
        Helper::checkCSRFToken();
        $username = $_POST['username'];  
        $password = $_POST['password'];      
        $confirm_password = $_POST['confirm_password'];

        // Leite Registrierung nur ein, wenn die Passwörter übereinstimmen  
        if ($password === $confirm_password) {
            UserController::createUser($username, $password);
        } else {
            header('Location: views/register_form.php?error=Passwörter%20stimmen%20nicht%20überein');
            exit();
        }
    }
    
    // Erzeuge Spielfeld, falls noch nicht geschehen und initialisiere die Rundenanzahl
    // mit -1 (das Anzeigen des leeren Feldes wird die Rundenanzahl auf 0, also vor dem 
    // ersten Zug erhöhen) 
    if (!isset($_SESSION["board"])) {
        $_SESSION["board"] = (new Board())->new();
        $_SESSION["round"] = -1;
    }

    // Erzeuge Instanz von GameController und führe #run mit der entsprechenden Methode aus
    $gameController = new GameController($_SESSION["board"], $_SESSION["round"]);
    $gameController->run($method);
}
?>
