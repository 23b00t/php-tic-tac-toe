<?php
// INFO: Steuere das Spiel  

class GameController
{
    // Initialisiere Spiel mit Board und Runde. Erzeuge benötigte Instanzen anderer Klassen.  
    function __construct(private $board, private $round)
    {
        $this->board = $board;
        $this->round = $round;
        $this->boardObj = new Board();
        $this->computer = new ComputerMove(); 
        $this->human = new HumanMove();
    }

    // Durchlaufe eine Runde  
    public function run($method)
    {
        if ($method === "POST") {
            // Spielfeld zurücksetzen, wenn entsprechender Button geklickt wurde
            isset($_POST["reset"]) && Helper::resetGame();
            isset($_POST["signout"]) && Helper::signOut();

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
                if (isset($_SESSION["modus"]) && Helper::isEven($this->round + $_SESSION["beginner"])) {
                    $this->board = $this->computer->makeMove($this->board, $this->round);
                } else {
                    // Menschlicher Zug  
                    // Bestimmen welcher Button geklickt wurde und speichere Zeichen basierend auf Rundenzahl 
                    $this->board = $this->human->makeMove($_POST, $this->board, $this->round);
                }
            }    

            // Auf Gewinn prüfen, wenn ein Gewinn möglich ist (also nach dem 5. Zug)
            $this->round > 3 && $this->board = $this->boardObj->checkForWin($this->board);

            // Rundenzahl erhöhen  
            $this->round++;

            // Unentschieden setzten, wenn 9 Züge gemacht wurden und kein Gewinn im letzten Zug stattfand
            ($this->round >= 9 && !isset($_SESSION["win"])) && $_SESSION["win"] = "false";

            // Ergebnis der Runde speichern  
            $_SESSION["round"] = $this->round;
            $_SESSION["board"] = $this->board;

            // Gib bei Gewinn oder Spielende (Unentschieden) eine entsprechende Mitteilung aus
            isset($_SESSION["win"]) && Helper::winMsg(); 

            header("location: /tic-tac-toe/app/src/views/game_view.php");
            exit();
        } else {
            // Nach jedem GET auf game.php
            $this->boardObj->draw($this->board);
        } 
    }
}
?>
