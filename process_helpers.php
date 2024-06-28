<?php

// TODO: Refactor in process_helpers.php (check if isEven() is still in scope of game.php)  
function saveSign($board, $point, $round) {
    $row_index = (int)$point[0];
    $col_index = (int)$point[2];
    $board[$row_index][$col_index] = isEven($round) ? "x" : "o";
    return $board;
}

function winMsg() {
    // Wenn die Session win true ist schreibe die erste Nachricht in winMsg, andernfalls die zweite
    $_SESSION["winMsg"] = 
        $_SESSION["win"] === "true"
        ? "<h3 class='text-center text-success-emphasis'> Gewinner ist " . $_SESSION["winner"] . "! </h3>"
        : "<h3 class='text-center text-success-emphasis'> Unentschieden! </h3>";
}

function resetGame() {
    session_unset();
    header("location: index.php");
    exit();
}

function isEven($int) {
    return $int % 2 == 0;
}

// https://craftytechie.com/how-to-copy-array-in-php/
function clone_array($arr) {
    $clone = array();
    foreach($arr as $k => $v) {
        if(is_array($v)) $clone[$k] = clone_array($v); //If a subarray
        else if(is_object($v)) $clone[$k] = clone $v; //If an object
        else $clone[$k] = $v; //Other primitive types.
    }
    return $clone;
}

function humanMove($post, $board, $round) {
    $point = array_keys($post)[0];
    return saveSign($board, $point, $round);
}

function processMove($post, $board, $round) {
    if (isset($_SESSION["modus"])) {
        // Computer ist dran? --> computer_move.php
        // Andernfalls gehe einfach weiter
        if (isEven($round)) {
            $board = computerMove($round, $board);
        } else {
            $board = humanMove($post, $board, $round);
        }
    } else {
        // Bestimmen welcher Button geklickt wurde und entsprechendes Zeichen 
        // in Spielfeld Array speichern. Es gibt nur zwei POST Optionen; wenn es 
        // reset war (guard clause), muss es ein Spielzug sein.  
        $board = humanMove($post, $board, $round);
    }
}
