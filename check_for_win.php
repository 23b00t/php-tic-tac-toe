<?php 
function checkForWin($board) {
    // Diagonalen prüfen; bei Match rufe win auf
    // Kann kein Treffer sein, wenn das mittlere Feld leer ist
    if (!empty($board[1][1])) {
        if ($board[0][0] == $board[1][1] && $board[1][1] == $board[2][2]) {
            $board[0][0] = $board[1][1] = $board[2][2] = win($board[1][1]);
        } elseif ($board[0][2] == $board[1][1] && $board[1][1] == $board[2][0]) {
            $board[0][2] = $board[1][1] = $board[2][0] = win($board[1][1]);
        }
    }

    // Reihen und Spalten prüfen, nutze Schleife über Spielfeld, um 6 Kombination in zwei
    // Ausdrücken zu prüfen. Teste auch wieder auf falsch positiv ('')  
    foreach ($board as $rowIdx => $row) {
        if ($row[0] == $row[1] && $row[1] == $row[2] && $row[1] != "") {
            $board[$rowIdx][0] = $board[$rowIdx][1] = $board[$rowIdx][2] = win($row[0]);
        } elseif (
            $board[0][$rowIdx] == $board[1][$rowIdx] && 
            $board[1][$rowIdx] == $board[2][$rowIdx] && 
            $board[1][$rowIdx] != "") {
            $board[0][$rowIdx] = $board[1][$rowIdx] = $board[2][$rowIdx] = win($board[0][$rowIdx]);
        }
    }
    return $board;
}

// Entscheide auf Gewinn, setzte Gewinner als x/o, formatiere Gewinnformation  
function win($value) {
    $_SESSION["win"] = "true";
    $_SESSION["winner"] = $value; 
    return "<strong>" . $value . "</strong>";
}
