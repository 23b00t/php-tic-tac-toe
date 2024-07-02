<?php

class GameController {
    function run($board, $round, $method) {
        $boardObj = new Board();
        $gameObj = new Game();
        $computer = new ComputerMove(); 
        $human = new HumanMove();

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
                    $board = $computer->makeMove($board, $round);
                } else {
                    // Menschlicher Zug  
                    // Bestimmen welcher Button geklickt wurde und speichere Zeichen basierend auf Rundenzahl 
                    $board = $human->makeMove($_POST, $board, $round);
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

}
