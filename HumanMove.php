<?php

class HumanMove {
	public $board;
	public $round;
	public $post;

	// Ermittelt die Koordinaten des geklickten Buttons und
	// übergibt sie zur Weiterverarbeitung.
	function makeMove($post, $board, $round) {
		$point = array_keys($post)[0];
		return $this->saveSign($board, $point, $round);
	}

	// Liest die Koordinaten aus und schreibt Abhänging von der Rundenzahl,
	// also wer dran ist, das entsprechende Zeichen ins Spielfeld. 
	function saveSign($board, $point, $round) {
		$row_index = (int)$point[0];
		$col_index = (int)$point[2];
		$board[$row_index][$col_index] = Helper::isEven($round + $_SESSION["beginner"]) ? "x" : "o";
		return $board;
	}
}
