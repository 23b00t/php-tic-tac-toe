<?php 
// INFO: Überprüfe alle 8 Gewinnkombination 

// HACK: Definiere eine benutzerdefinierte Exception und nutze sie,
// um bei der Gewinnprüfung durch den Computer der nach seinem Zug 
// sucht, das origanel Spielfeld nicht zu manipulieren und nur die 
// Mitteilung zurückzugeben, dass dies ein Gewinnzug war.
class TestException extends Exception {}

function checkForWin($board, $testCase = false) {
    // Hauptblock wenn es kein Testcase ist oder dieser ohne Gewinn verläuft
    try {
        // Diagonalen prüfen; bei Match rufe win auf
        // Kann kein Treffer sein, wenn das mittlere Feld leer ist
        if (!empty($board[1][1])) {
            if ($board[0][0] === $board[1][1] && $board[1][1] === $board[2][2]) {
                $board[0][0] = $board[1][1] = $board[2][2] = win($board[1][1], $testCase);
            } elseif ($board[0][2] === $board[1][1] && $board[1][1] === $board[2][0]) {
                $board[0][2] = $board[1][1] = $board[2][0] = win($board[1][1], $testCase);
            }
        }
    
        // Reihen und Spalten prüfen, nutze Schleife über Spielfeld, um 6 Kombination in zwei
        // Ausdrücken zu prüfen. Teste auch wieder auf falsch positiv ('')  
        for ($i = 0; $i < 3; $i++) { 
            if ($board[$i][0] === $board[$i][1] && $board[$i][1] === $board[$i][2] && $board[$i][0] !== '') {
                $board[$i][0] = $board[$i][1] = $board[$i][2] = win($board[$i][0], $testCase);
            } elseif ($board[0][$i] === $board[1][$i] && $board[1][$i] === $board[2][$i] && $board[0][$i] !== '') {
                $board[0][$i] = $board[1][$i] = $board[2][$i] = win($board[0][$i], $testCase);
            } 
        }

        return $board;
    // Fange die Ausnahme auf, falls in einem Testcase ein Gewinn festgestellt wurde
    } catch (TestException) {
        return true;
    }
}

function win($value, $testCase) {
    // Wenn $testCase ture ist, also der Computer einen Zug überprüft und auf einen Gewinn  
    // getroffen ist, löse die Ausnahme aus, damit der Treffer von catch abgefangen werden kann.  
    if ($testCase) {
        // In diesem speziellen Fall ist das übermitteln der Exception message nicht nötig
        // was bei aktuellen PHP Versionen optional ist >= v8.0.0
        throw new TestException();     
    // Entscheide auf Gewinn, setzte Gewinner als x/o, formatiere Gewinnformation  
    } else {
        $_SESSION["win"] = "true";
        $_SESSION["winner"] = $value; 
        return "<strong>" . $value . "</strong>";
    }
}
