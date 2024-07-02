<?php
// INFO: Verarbeitungscontroller

// TODO: Verzögerung nach Computerzug; Problem: Darstellung des Spielerzugs wird verzögert...
// TODO: Logik um per Zufall zu entscheiden wer das Spiel beginnt  

// Überprüfe, ob keine Session aktiv ist, dann starte eine  
session_status() === PHP_SESSION_NONE && session_start();

// Binde Dateien ein
require_once __DIR__ . '/HumanMove.php';
require_once __DIR__ . '/Helper.php';
require_once __DIR__ . '/Game.php';
require_once __DIR__ . '/Board.php';
require_once __DIR__ . '/ComputerMove.php';
require_once __DIR__ . '/GameController.php';


// HACK: Simuliere POST und GET per übergabe als String Argument an run
// --> Lösung für Züge durch Computergegener

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
$_SERVER["REQUEST_METHOD"] === "POST" && init("POST");

function init($method) {
    if (!isset($_SESSION["board"])) {
        $_SESSION["board"] = (new Board())->new();
        $_SESSION["round"] = -1;
    }
    
    $gameController = new GameController();
    $gameController->run($_SESSION["board"], $_SESSION["round"], $method);
}

