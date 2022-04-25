<?php
require "factories/board_factory.php";
require "models/player.php";
require "models/dice.php";
require "models/game.php";

session_start();
$boardFactory = new BoardFactory();
$board = $boardFactory->create(36);

$playerOne = !isset($_SESSION['playerOne'])
    ? new Player("Player1", 0, 0)
    : $_SESSION['playerOne'];

$playerTwo = !isset($_SESSION['playerTwo'])
    ? new Player("Player2", 0, 0)
    : $_SESSION['playerTwo'];


$dice = new Dice(6);

$game = new Game($playerOne, $playerTwo, $board, $dice, Game::$PLAYER_ONE_TURN, 1, Game::$STATE_IN_PROGRESS);


if (isset($_POST['action']) && $_POST['action'] == "roll") {
    $game->rollDice();
}

if (isset($_POST['action']) && $_POST['action'] == "reset") {
    $game->reset();
}

$game->render();

$_SESSION['playerOne'] = $game->playerOne;
$_SESSION['playerTwo'] = $game->playerTwo;
?>

<form action="" method="post">

    <input type="hidden" name="action" value="roll" />
    <button type="submit"> Roll Dice </button>

</form>
<form action="" method="post">

    <input type="hidden" name="action" value="reset" />
    <button type="submit"> Reset Game </button>

</form>