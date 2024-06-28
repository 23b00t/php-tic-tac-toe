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
        $round = -1;
    }
    run($board, $round, $method);
}

function run($board, $round, $method) {
    if ($method === "POST") {
        // Spielfeld zurücksetzen, wenn entsprechender Button geklickt wurde
        isset($_POST["reset"]) && resetGame();
    
        // Prüfe, ob Computergegner gewählt wurde. Falls ja übergebe dies an eine SESSION
        // und mache den ersten Zug.  Andernfalls lade game.php, um das Spiel normal zu beginnen.  
        // Falls die Session bereits gesetzt wurde prüfe, ob der Computer
        // wieder an der Reihe ist ($round.odd?) und mache entweder den nächsten Zug oder nichts.  
        
        if (isset($_POST["opponent"])) {
            if ($_POST["switch"] === "computer") {
                $_SESSION["modus"] = "computer";
            }
        } else {
            if (isset($_SESSION["modus"]) && isEven($round)) {
                // Computer ist dran? --> computer_move.php
                $board = computerMove($round, $board);
            } else {
                // Bestimmen welcher Button geklickt wurde und entsprechendes Zeichen 
                // in Spielfeld Array speichern. 
                $board = humanMove($_POST, $board, $round);
            }
        }    

        // Nur auf Gewinn prüfen, wenn ein Gewinn möglich ist
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

