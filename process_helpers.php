<?php
// INFO: Hilfsfunktionen  

// Ermittelt die Koordinaten des geklickten Buttons und
// übergibt sie zur Weiterverarbeitung.
function humanMove($post, $board, $round) {
    $point = array_keys($post)[0];
    return saveSign($board, $point, $round);
}

// Liest die Koordinaten aus und schreibt Abhänging von der Rundenzahl,
// also wer dran ist, das entsprechende Zeichen ins Spielfeld. 
function saveSign($board, $point, $round) {
    $row_index = (int)$point[0];
    $col_index = (int)$point[2];
    $board[$row_index][$col_index] = isEven($round) ? "x" : "o";
    return $board;
}

// Erzeugt eine Nachricht über den Spielausgang
function winMsg() {
    // Wenn die Session win true ist schreibe die erste Nachricht in winMsg, andernfalls die zweite
    $_SESSION["winMsg"] = 
        $_SESSION["win"] === "true"
        ? "<h3 class='text-center text-success-emphasis'> Gewinner ist " . $_SESSION["winner"] . "! </h3>"
        : "<h3 class='text-center text-success-emphasis'> Unentschieden! </h3>";
}

// Startet das Spiel neu
function resetGame() {
    session_unset();
    header("location: index.php");
    exit();
}

// ruby: #even? ;)
function isEven($int) {
    return $int % 2 == 0;
}

// Erzuegt einen Clone eines Arrays, auch eines Multidimensionalen.
// Dies ist nötig um Komplikationen bei der Bewertung der Züge durch
// den Computer zu vermeiden, die durch mehrfaches verwenden und ändern
// von Speicher bei einem einfachen Verweis auf die Speicherstelle entsteht.
// thx to: https://craftytechie.com/how-to-copy-array-in-php/ for this perfect solution!
function clone_array($arr) {
    $clone = array();
    foreach($arr as $k => $v) {
        if(is_array($v)) $clone[$k] = clone_array($v); //If a subarray
        else if(is_object($v)) $clone[$k] = clone $v; //If an object
        else $clone[$k] = $v; //Other primitive types.
    }
    return $clone;
}

