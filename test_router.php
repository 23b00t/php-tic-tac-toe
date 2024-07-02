<?php
require_once __DIR__ . '/HumanMove.php';
require_once __DIR__ . '/Helper.php';
require_once __DIR__ . '/Game.php';
require_once __DIR__ . '/Board.php';
require_once __DIR__ . '/ComputerMove.php';

$board = new Board()->new();
$newBoard = $board->new();

// print_r($newBoard);
// $board->draw($newBoard);
$game = new HumanMove();
$move = $game->makeMove([["0-0"]], $newBoard, 0);
print_r($move);

