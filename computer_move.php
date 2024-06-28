<?php
// INFO: Logik um den Computer einen Zug machen zu lassen

// TODO: Ermittle wieviele Züge noch offen sind und wieviele mögliche Kombinationen
// es gibt. Ermittle in Schleife, die check_for_win.php aufruft, was alles mögliche 
// Züge sind und wähle den Besten. 

function computerMove($round, $board) {
	// finde freie Stellen, also '' im $board
	$indexes = [];
	foreach ($board as $rowIdx => $row) {
        // $indexes[$rowIdx][] = array_search('', array_column($board, $rowIdx));
		foreach ($row as $colIdx => $value) {
			$value === '' && $indexes[] = [$rowIdx, $colIdx];
		}
	}

	// INFO: Die folgenden zwei for-loops zusammenzufassen funktioniert aus irgendeinem
	// Grund nicht - Wieso? Außerdem ist es nötig eine Deep Copy zu erstellen.
	
	// Wieviele Optionen hat Computer (Stellen an denen ein '' steht)
	$possibleMoves = count($indexes);
	// Überprüfe, ob Computer in diesem Zug gewinnen kann und ziehe entsprechend
	for ($option = 0; $option  < $possibleMoves; $option ++) { 
		$point = $indexes[$option];
		$testBoard = clone_array($board);
		$testBoard[$point[0]][$point[1]] = 'x';
		$testMove = checkForWin($testBoard, true);
		if ($testMove === 'win') {
			$board = $testBoard;
			// throw new Exception();
			return $board;
		} 
	}

	for ($option = 0; $option  < $possibleMoves; $option ++) { 
		// Überprüfe, ob Gegener in diesem Zug gewinnen kann und ziehe entsprechend
		$point = $indexes[$option];
		$testBoard = clone_array($board);
		$testBoard[$point[0]][$point[1]] = 'o';
		$testMove = checkForWin($testBoard, true);
		if ($testMove === 'win') {
			$board[$point[0]][$point[1]]= 'x';
            // throw new Exception();
			return $board;
		}
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
