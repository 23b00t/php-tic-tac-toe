<?php

class Game {
	// Erzeugt eine Nachricht über den Spielausgang
	public function winMsg() {
		// Wenn die Session win true ist schreibe die erste Nachricht in winMsg, andernfalls die zweite
		$_SESSION["winMsg"] = 
		$_SESSION["win"] === "true"
		? "<h3 class='text-center text-success-emphasis'> Gewinner ist " . $_SESSION["winner"] . "! </h3>"
		: "<h3 class='text-center text-success-emphasis'> Unentschieden! </h3>";
	}

	// Startet das Spiel neu
	public function resetGame() {
		session_unset();
		header("location: index.php");
		exit();
	}
}
