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
		// session_unset();
		unset($_SESSION['board']);
		unset($_SESSION['round']);
		unset($_SESSION['winMsg']);
		unset($_SESSION['win']);
		unset($_SESSION['winner']);
		unset($_SESSION['modus']);
		unset($_SESSION['beginner']);
		unset($_SESSION['game_controller']);
		header("location: /tic-tac-toe/app/src/views/options_view.php");
		exit();
	}

	public static function signOut() {
		session_unset();
		session_destroy();
		header("location: ../public/index.php");
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

	// CSRF-Token überprüfen
	public static function checkCSRFToken() {
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			// Token ungültig oder nicht vorhanden
			die('Ungültiger CSRF-Token');
		}
	}

	public static function generateCSRFToken() {
		// Session starten
		session_status() === PHP_SESSION_NONE && session_start();
		// CSRF-Token generieren, falls es nicht bereits existiert
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		return $_SESSION['csrf_token'];
	}
}
?>
