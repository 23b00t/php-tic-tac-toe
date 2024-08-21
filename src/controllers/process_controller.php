<?php
// INFO: Verarbeitungscontroller

// Überprüfe, ob keine Session aktiv ist, dann starte eine  
session_status() === PHP_SESSION_NONE && session_start();

// Binde Dateien ein
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/models/HumanMove.php');
require_once(__ROOT__.'/helpers/Helper.php');
require_once(__ROOT__.'/models/Board.php');
require_once(__ROOT__.'/models/ComputerMove.php');
require_once(__ROOT__.'/controllers/GameController.php');

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
$_SERVER["REQUEST_METHOD"] === "POST" && init("POST");

// HACK: Simuliere POST und GET per übergabe als String Argument an run
// --> Lösung für Züge durch Computergegener

function init($method) {
    if (!isset($_SESSION["board"])) {
        $_SESSION["board"] = (new Board())->new();
        $_SESSION["round"] = -1;
    }
    
    $gameController = new GameController($_SESSION["board"], $_SESSION["round"]);
    $gameController->run($method);
}

