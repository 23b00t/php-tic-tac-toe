<?php
// INFO: Behandle alle Board aktionen

// HACK: Definiere eine benutzerdefinierte Exception und nutze sie,
// um bei der Gewinnprüfung durch den Computer der nach seinem Zug 
// sucht, das origanel Spielfeld nicht zu manipulieren und nur die 
// Mitteilung zurückzugeben, dass dies ein Gewinnzug war.
class TestException extends Exception
{
}

class Board
{
    public function new()
    {
        return [["", "", ""], ["", "", ""], ["", "", ""]];
    }

    public function draw($board)
    {
        for ($row = 0; $row < 3; $row++) { 
            echo "<tr>";
            for ($column = 0; $column < 3; $column++) { 
                // Bereite die aktuelle Position als String vor, z. B.: 2-1
                $position = $row . "-" . $column;
                // Lese das Zeichen an der Position aus, 'x' || 'o' || ''
                $sign = $board[$row][$column];

                // Funktionalität um bereits genutzte Felder zu deaktivieren ($sign != "" ? "disabled" : "")
                // strpos prüft ob das strong tag enthalten ist (was Gewinn anzeigt) und ändert dann die Farbe
                // der Gewinnbuttons auf rot.  
                echo "<th style='width:120px; height:120px'>
					<button name=$position value='$sign' class='btn "
                 . (strpos($sign, 'strong') ? "btn-danger" : "btn-outline-info") .
                 " pb-4 d-flex justify-content-center align-items-center' "
                 . (($sign != "" || isset($_SESSION["win"])) ? "disabled" : "") .
                 " type='submit' style='width:120px; height:120px; font-size:10rem;'> $sign </button>
				</th>";
            }
            echo "</tr>";
        }
    }

    public function checkForWin($board, $testCase = false)
    {
        // Hauptblock wenn es kein Testcase ist oder dieser ohne Gewinn verläuft
        try {
            // Diagonalen prüfen; bei Match rufe win auf
            // Kann kein Treffer sein, wenn das mittlere Feld leer ist
            if (!empty($board[1][1])) {
                if ($board[0][0] === $board[1][1] && $board[1][1] === $board[2][2]) {
                    $board[0][0] = $board[1][1] = $board[2][2] = $this->win($board[1][1], $testCase);
                } elseif ($board[0][2] === $board[1][1] && $board[1][1] === $board[2][0]) {
                    $board[0][2] = $board[1][1] = $board[2][0] = $this->win($board[1][1], $testCase);
                }
            }

            // Reihen und Spalten prüfen, nutze Schleife über Spielfeld, um 6 Kombination in zwei
            // Ausdrücken zu prüfen. Teste auch wieder auf falsch positiv ('')  
            for ($i = 0; $i < 3; $i++) { 
                if ($board[$i][0] === $board[$i][1] && $board[$i][1] === $board[$i][2] && $board[$i][0] !== '') {
                    $board[$i][0] = $board[$i][1] = $board[$i][2] = $this->win($board[$i][0], $testCase);
                } elseif ($board[0][$i] === $board[1][$i] && $board[1][$i] === $board[2][$i] && $board[0][$i] !== '') {
                    $board[0][$i] = $board[1][$i] = $board[2][$i] = $this->win($board[0][$i], $testCase);
                } 
            }

            return $board;
            // Fange die Ausnahme auf, falls in einem Testcase ein Gewinn festgestellt wurde
        } catch (TestException) {
            return true;
        }
    }

    private function win($value, $testCase)
    {
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
}
?>
