<?php
use Game\Domino;

require_once __DIR__ . '/vendor/autoload.php';

// Played by a minimum of 2 players and a max of 4.
if (!isset($argv[1])) die("No arguments given, default game to 2 players...\n");
if ($argv[1] <= 1) die("Sorry, the game is played by a minimum of 2 players and a max of 4. Try again...\n");

$domino = new Domino($argv[1]);
$domino->createGame();