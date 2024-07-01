<?php
// INFO: Verarbeitungscontroller

// TODO: Verzögerung nach Computerzug; Problem: Darstellung des Spielerzugs wird verzögert...
// TODO: Logik um per Zufall zu entscheiden wer das Spiel beginnt  

// Überprüfe, ob keine Session aktiv ist, dann starte eine  
session_status() === PHP_SESSION_NONE && session_start();

// Binde Dateien ein
require_once __DIR__ . '/check_for_win.php';
require_once __DIR__ . '/draw_board.php';
require_once __DIR__ . '/computer_move.php';
require_once __DIR__ . '/process_helpers.php';


// HACK: Simuliere POST und GET per übergabe als String Argument an run
// --> Lösung für Züge durch Computergegener

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
$_SERVER["REQUEST_METHOD"] === "POST" && init("POST");

function init($method) {
    if (isset($_SESSION["board"])) {
        $round = $_SESSION["round"];
        $board = $_SESSION["board"];
    } else {
        $board = [["", "", ""], ["", "", ""], ["", "", ""]];
        // auf -1 runter gegeangen, um ersten POST der die Spielart wählt aufzufangen  
        // Logik von $round: Nach jedem Zug entspricht $round der Anzahl der gemachten Züge  
        $round = -1;
    }
    run($board, $round, $method);
}

function run($board, $round, $method) {
    if ($method === "POST") {
        // Spielfeld zurücksetzen, wenn entsprechender Button geklickt wurde
        isset($_POST["reset"]) && resetGame();
    
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
            if (isset($_SESSION["modus"]) && isEven($round + $_SESSION["beginner"])) {
                $board = computerMove($board, $round);
            // Menschlicher Zug  
            } else {
                // Bestimmen welcher Button geklickt wurde und speichere Zeichen basierend auf Rundenzahl 
                $board = humanMove($_POST, $board, $round);
            }
        }    

        // Auf Gewinn prüfen, wenn ein Gewinn möglich ist (also nach dem 5. Zug)
        $round > 3 && $board = checkForWin($board);
            
        $round++;

        // Unentschieden setzten, wenn 9 Züge gemacht wurden und kein Gewinn im letzten Zug stattfand
        ($round >= 9 && !isset($_SESSION["win"])) && $_SESSION["win"] = "false";
    
	    $_SESSION["round"] = $round;
	    $_SESSION["board"] = $board;

        // Gib bei Gewinn oder Spielende (Unentschieden) eine entsprechende Mitteilung aus
        isset($_SESSION["win"]) && winMsg(); 
        
        header("location: game.php");
    } else {
        // Nach jedem GET auf game.php
        drawBoard($board);
    } 
}

