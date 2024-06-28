<?php
// INFO: Logik um den Computer einen Zug machen zu lassen
// TODO: Überprüfe, ob Gegener in diesem Zug gewinnen kann und ziehe entsprechend
// TODO: Ermittle wieviele Züge noch offen sind und wieviele mögliche Kombinationen
// es gibt. Ermittle in Schleife, die check_for_win.php aufruft, was alles mögliche 
// Züge sind und wähle den Besten. 

function computerMove($round, $board) {
	
    // TODO: Überprüfe, ob Computer in diesem Zug gewinnen kann und ziehe entsprechend
	
	// TODOen: Ansonsten mache einen Zufallszug.
	$indexes = [];
	foreach ($board as $rowIdx => $row) {
        // $indexes[$rowIdx][] = array_search('', array_column($board, $rowIdx));
		foreach ($row as $colIdx => $value) {
			$value === '' && $indexes[] = [$rowIdx, $colIdx];
		}
	}

	// Wieviele Optionen hat Computer (Stellen an denen ein '' steht)
	$possibleMoves = count($indexes);
	// Bestimme eine Zufallszahl in diesem Bereich
	$randMoveNum = rand(0, $possibleMoves - 1);
	// Wähle ein Koordinaten Paar an dieser Zufallsstelle
	$randMove = $indexes[$randMoveNum];
	// Trage auf $board an dieser Stelle das Computerzeichen ein  
	$board[$randMove[0]][$randMove[1]] = 'x';

	return $board;
}
