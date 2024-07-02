<?php
// INFO: Verarbeitungscontroller

// Überprüfe, ob keine Session aktiv ist, dann starte eine  
session_status() === PHP_SESSION_NONE && session_start();

// Binde Dateien ein
require_once('../src/Model/HumanMove.php');
require_once('../src/Model/Helper.php');
require_once('../src/Model/Board.php');
require_once('../src/Model/ComputerMove.php');
require_once('../src/Controller/GameController.php');

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

