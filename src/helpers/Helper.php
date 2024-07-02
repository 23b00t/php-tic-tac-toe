<?php
class Helper {
	// ruby: #even? ;)
	public static function isEven($int) {
		return $int % 2 == 0;
	}

	// Erzeugt eine Nachricht über den Spielausgang
	public static function winMsg() {
		// Wenn die Session win true ist schreibe die erste Nachricht in winMsg, andernfalls die zweite
		$_SESSION["winMsg"] = 
		$_SESSION["win"] === "true"
		? "<h3 class='text-center text-success-emphasis'> Gewinner ist " . $_SESSION["winner"] . "! </h3>"
		: "<h3 class='text-center text-success-emphasis'> Unentschieden! </h3>";
	}

	// Startet das Spiel neu
	public static function resetGame() {
		session_unset();
		header("location: index.php");
		exit();
	}

	// Erzuegt einen Clone eines Arrays, auch eines Multidimensionalen.
	// Dies ist nötig um Komplikationen bei der Bewertung der Züge durch
	// den Computer zu vermeiden, die durch mehrfaches verwenden und ändern
	// von Speicher bei einem einfachen Verweis auf die Speicherstelle entsteht.
	// thx to: https://craftytechie.com/how-to-copy-array-in-php/ for this perfect solution!
	public static function clone_array($arr) {
		$clone = array();
		foreach($arr as $k => $v) {
			if(is_array($v)) $clone[$k] = Helper::clone_array($v); //If a subarray
			else if(is_object($v)) $clone[$k] = clone $v; //If an object
			else $clone[$k] = $v; //Other primitive types.
		}
		return $clone;
	}
}
