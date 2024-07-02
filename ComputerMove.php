<?php
class ComputerMove {
	// INFO: Logik um den Computer einen Zug machen zu lassen

	// TODO: Ermittle wieviele Züge noch offen sind und wieviele mögliche Kombinationen
	// es gibt. Ermittle in Schleife, die check_for_win.php aufruft, was alles mögliche 
	// Züge sind und wähle den Besten. 
	// Siehe: https://de.wikipedia.org/wiki/Minimax-Algorithmus#Algorithmus
	// Oder lege Strategie für Computer fest, z. B. bevorzuge die Ecken.  

	public function makeMove($board, $round) {
		// finde freie Stellen, also '' im $board
		$indexes = [];
		foreach ($board as $rowIdx => $row) {
			foreach ($row as $colIdx => $value) {
				$value === '' && $indexes[] = [$rowIdx, $colIdx];
			}
		}

		$possibleMoves = count($indexes);

		// NOTE: Es müssen erst alle Optionen auf Gewinn geprüft werden und nur wenn keine
		// gefunden wird sind die Option zur Gewinnvereitelung relevant. 

		// Falls der Spieler beginnt, muss vor seinem 3. Zug auf Gewinn durch den Menschen geprüft werden
		if ($round > 2) {
			// Überprüfe, ob Computer in diesem Zug gewinnen kann und ziehe entsprechend
			$moveWin = $this->checkMove('x', $board, $indexes);
			if ($moveWin) return $moveWin;

			// Überprüfen, ob Gegener im nächsten Zug gewinnen kann; falls ja Zug vereiteln  
			$move = $this->checkMove('o', $board, $indexes);
			if ($move) return $move;
		}

		// Ansonsten mache einen Zufallszug.
		// Bestimme eine Zufallszahl in diesem Bereich
		$randPointIdx = rand(0, $possibleMoves - 1);
		// Wähle ein Koordinaten Paar an dieser Zufallsstelle
		$randPoint = $indexes[$randPointIdx];
		// Trage auf $board an dieser Stelle das Computerzeichen ein  
		$board[$randPoint[0]][$randPoint[1]] = 'x';

		return $board;
	}

	// Durchlaufe alle freien Felder und überprüfe, ob durch ein Setzten an dieser
	// Stelle ein Gewinn möglich ist
	private function checkMove($sign, $board, $indexes) {
		foreach ($indexes as $point) {
			// Clone originales Spielfeldarray, um Komplikationen durch fortlaufende
			// Manipulation des referenzierten Speicherbereichs zu vermeiden.  
			$testBoard = Helper::clone_array($board);
			// Setze übergebenes Zeichen an Stelle auf Testboard  
			$testBoard[$point[0]][$point[1]] = $sign;
			// Überprüfe, ob mit diesem Zug ein Gewinn vorliegt
			$boardObj = new Board();
			$testMove = $boardObj->checkForWin($testBoard, true);
			// Falls ja, setzt dort und gebe das manipulierte Spielfeld zurück
			if ($testMove === true) {
				$testBoard[$point[0]][$point[1]] = 'x';
				return $testBoard;
			} 
		}
		// Es wurde kein Gewinn gefunden.  Gebe false zurück um NULL zu vermeiden.  
		return false;
	}
}
