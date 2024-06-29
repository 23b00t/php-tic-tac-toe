<?php
// INFO: Logik um den Computer einen Zug machen zu lassen

// TODO: Ermittle wieviele Züge noch offen sind und wieviele mögliche Kombinationen
// es gibt. Ermittle in Schleife, die check_for_win.php aufruft, was alles mögliche 
// Züge sind und wähle den Besten. 

function computerMove($round, $board) {
	// finde freie Stellen, also '' im $board
	$indexes = [];
	foreach ($board as $rowIdx => $row) {
		foreach ($row as $colIdx => $value) {
			$value === '' && $indexes[] = [$rowIdx, $colIdx];
		}
	}

	// Wieviele Optionen hat Computer (Stellen an denen ein '' steht)
	$possibleMoves = count($indexes);

	// NOTE: Es müssen erst alle Optionen auf Gewinn geprüft werden und nur wenn keine
	// gefunden wird sind die Option zur Gewinnvereitelung relevant. 

	// Gewinnmöglichkeit erst vor 5. Zug relevant  
	if ($round > 3) {
	    // Überprüfe, ob Computer in diesem Zug gewinnen kann und ziehe entsprechend
	    $moveWin = checkMove('x', $board, $indexes, $possibleMoves);
	    if ($moveWin) return $moveWin;
	    
	    // Überprüfen, ob Gegener im nächsten Zug gewinnen kann; falls ja Zug vereiteln  
	    $move = checkMove('o', $board, $indexes, $possibleMoves);
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
function checkMove($sign, $board, $indexes, $possibleMoves) {
	for ($option = 0; $option  < $possibleMoves; $option ++) { 
		// Wähle freies Feld
		$point = $indexes[$option];
		// Clone originales Spielfeldarray, um Komplikationen durch fortlaufende
		// Manipulation des referenzierten Speicherbereichs zu vermeiden.  
		$testBoard = clone_array($board);
		// Setze übergebenes Zeichen an Stelle auf Testboard  
		$testBoard[$point[0]][$point[1]] = $sign;
		// Überprüfe, ob mit diesem Zug ein Gewinn vorliegt
		$testMove = checkForWin($testBoard, true);
		// Falls ja, setzt dort und gebe das manipulierte Spielfeld zurück
		if ($testMove === true) {
            $testBoard[$point[0]][$point[1]] = 'x';
			return $testBoard;
		} 
	}
	// Es wurde kein Gewinn gefunden.  Gebe false zurück um NULL zu vermeiden.  
	return false;
}
