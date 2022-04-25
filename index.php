<?php
define('GAME_SESSION_KEY', 'game');
require "factories/board_factory.php";
require "models/player.php";
require "models/dice.php";
require "models/game.php";

/**
 * SESSION will have shape of
 * SESSION -> Game
 * Game -> Player 1 etc
 */

session_start();
$boardFactory = new BoardFactory();
$board = $boardFactory->create(36);

$dice = new Dice(6);

$game = !isset($_SESSION[GAME_SESSION_KEY])
    ? new Game(new Player("Player1", 0, 0), new Player("Player2", 0, 0), $board, $dice, Game::$PLAYER_ONE_TURN, 1, Game::$STATE_IN_PROGRESS)
    : $_SESSION[GAME_SESSION_KEY];


if (isset($_POST['action']) && $_POST['action'] == "roll") {
    $game->rollDice();
}

if (isset($_POST['action']) && $_POST['action'] == "reset") {
    $game->reset();
}

$game->render();

$_SESSION[GAME_SESSION_KEY] = $game;
?>

<form action="" method="post">

    <input type="hidden" name="action" value="roll" />
    <button type="submit"> Roll Dice </button>

</form>
<form action="" method="post">

    <input type="hidden" name="action" value="reset" />
    <button type="submit"> Reset Game </button>

</form>