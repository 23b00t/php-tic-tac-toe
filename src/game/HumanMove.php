<?php
// Zug durch Eingabe eines Spielers
class HumanMove
{
    // Ermittelt die Koordinaten des geklickten Buttons und
    // übergibt sie zur Weiterverarbeitung.
    public function makeMove($post, $board, $round)
    {
        $point = array_keys($post)[0];
        return $this->_saveSign($board, $point, $round);
    }

    // Liest die Koordinaten aus und schreibt Abhänging von der Rundenzahl,
    // also wer dran ist, das entsprechende Zeichen ins Spielfeld. 
    private function _saveSign($board, $point, $round)
    {
        $row_index = (int)$point[0];
        $col_index = (int)$point[2];
        $beginner = isset($_SESSION["beginner"]) ? $_SESSION["beginner"] : 0;
        $board[$row_index][$col_index] = Helper::isEven($round + $beginner) ? "x" : "o";
        return $board;
    }
}
