<?php
// Überprüfe, ob keine Session aktiv ist, dann starte eine  
// === : keine Typumwandlung bei Vergleich
// Syntax: condition && action 
session_status() === PHP_SESSION_NONE && session_start();
require_once __DIR__ . '/check_for_win.php';
require_once __DIR__ . '/draw_board.php';

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    init("POST");
}

function init($method) {
    if (isset($_SESSION["board"])) {
        $round = $_SESSION["round"];
        $board = $_SESSION["board"];
    } else {
        $board = [["", "", ""], ["", "", ""], ["", "", ""]];
        $round = 0;
    }
    run($board, $round, $method);
}

function run($board, $round, $method) {
    if ($method === "POST") {
        // Spielfeld zurücksetzen, wenn entsprechender Button geklickt wurde
        isset($_POST["reset"]) && resetGame();
    
        // TODO: Prüfe, ob Computergegner gewählt wurde. Falls ja übergebe dies an eine SESSION
        // und mache den ersten Zug.  Andernfalls lade game.php, um das Spiel normal zu beginnen.  
        // Falls die Session bereits gesetzt wurde prüfe, ob der Computer
        // wieder an der Reihe ist ($round.odd?) und mache entweder den nächsten Zug oder nichts.  
        
        // TODO: Logik um per Zufall zu entscheiden wer das Spiel beginnt  

        // Bestimmen welcher Button geklickt wurde und entsprechendes Zeichen 
        // in Spielfeld Array speichern. Es gibt nur zwei POST Optionen; wenn es 
        // reset war (guard clause), muss es ein Spielzug sein.  
        // INFO: Diese Zeilen sind nur Aufzurufen, wenn der Computer nicht dran ist 
        $point = array_keys($_POST)[0];
        $board = saveSign($board, $point, $round);
        // INFO: ---------------------------------------|
    
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

function saveSign($board, $point, $round) {
    $row_index = (int)$point[0];
    $col_index = (int)$point[2];
    $board[$row_index][$col_index] = $round % 2 == 0 ? "x" : "o";
    return $board;
}

function winMsg() {
    // Wenn die Session win true ist schreibe die erste Nachricht in winMsg, andernfalls die zweite
    $_SESSION["winMsg"] = 
        $_SESSION["win"] === "true"
        ? "<h3 class='text-center text-success-emphasis'> Gewinner ist " . $_SESSION["winner"] . "! </h3>"
        : "<h3 class='text-center text-success-emphasis'> Unentschieden! </h3>";
}

function resetGame() {
    session_unset();
    header("location: game.php");
    exit();
}
