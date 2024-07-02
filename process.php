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


// HACK: Simuliere POST und GET per übergabe als String Argument an run
// --> Lösung für Züge durch Computergegener

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
$_SERVER["REQUEST_METHOD"] === "POST" && init("POST");

function init($method) {
    if (isset($_SESSION["board"])) {
        $round = $_SESSION["round"];
        $board = $_SESSION["board"];
    } else {
        $boardObj = new Board();
        $board = $boardObj->new();
        // auf -1 runter gegeangen, um ersten POST der die Spielart wählt aufzufangen  
        // Logik von $round: Nach jedem Zug entspricht $round der Anzahl der gemachten Züge  
        $round = -1;
    }
    run($board, $round, $method);
}

function run($board, $round, $method) {
    $boardObj = new Board();
    $gameObj = new Game();
    if ($method === "POST") {
        // Spielfeld zurücksetzen, wenn entsprechender Button geklickt wurde
        isset($_POST["reset"]) && $gameObj->resetGame();

        // INFO: Prüfe, ob Computergegner gewählt wurde.  Falls ja übergebe dies an eine SESSION. 
        // Starte Spiel.  Falls die Session modus: computer gesetzt wurde prüfe, ob der Computer 
        // an der Reihe ist (gerade Runde).  In jedem anderen Fall behandle den menschlichen Spielerzug. 

        if (isset($_POST["opponent"])) {
            if ($_POST["switch"] === "computer") {
                $_SESSION["modus"] = "computer";
                $_SESSION["beginner"] = rand(0, 1);
            }
        } else {
            // Computer ist dran? 
            if (isset($_SESSION["modus"]) && Helper::isEven($round + $_SESSION["beginner"])) {
                $computer = new ComputerMove(); 
                $board = $computer->makeMove($board, $round);
                // Menschlicher Zug  
            } else {
                $human = new HumanMove();
                $board = $human->makeMove($_POST, $board, $round);
                // Bestimmen welcher Button geklickt wurde und speichere Zeichen basierend auf Rundenzahl 
            }
        }    

        // Auf Gewinn prüfen, wenn ein Gewinn möglich ist (also nach dem 5. Zug)
        $round > 3 && $board = $boardObj->checkForWin($board);

        $round++;

        // Unentschieden setzten, wenn 9 Züge gemacht wurden und kein Gewinn im letzten Zug stattfand
        ($round >= 9 && !isset($_SESSION["win"])) && $_SESSION["win"] = "false";

        $_SESSION["round"] = $round;
        $_SESSION["board"] = $board;

        // Gib bei Gewinn oder Spielende (Unentschieden) eine entsprechende Mitteilung aus
        isset($_SESSION["win"]) && $gameObj->winMsg(); 

        header("location: game.php");
    } else {
        // Nach jedem GET auf game.php
        $boardObj->draw($board);
    } 
}

