<?php

define('GAME_SESSION_KEY', 'game');
require "system/db.php";
require "system/actions.php";
require "system/action_handler.php";
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

$db = new DB();

function createGame()
{
    $dice = new Dice(6);
    $game = null;
    if (!isset($_SESSION[GAME_SESSION_KEY])) {
        $boardFactory = new BoardFactory();
        $board = $boardFactory->create(36);

        $game = new Game(new Player("Player1", 0, 0), new Player("Player2", 0, 0), $board, $dice, Game::$PLAYER_ONE_TURN, 1, Game::$STATE_IN_PROGRESS);
    } else {
        $game =  $_SESSION[GAME_SESSION_KEY];
    }
    return $game;
}
$game = createGame();

handleActions(function ($action) use ($game, $db) {
    switch ($action) {
        case ACTION_ROLL:
            $game->rollDice();
            break;
        case ACTION_RESET:
            session_destroy();
            $game = createGame();
            $game->reset();
            break;
        case ACTION_SAVE:
            $gameId = $game->saveGame($db);
            echo "Game saved! Use game ID " . $gameId . " to load the game!";
            session_destroy();
            $game = createGame();
            $game->reset();
            break;
        case ACTION_LOAD:
            $game = $game->loadGame($db, $_POST['game_id']);
            if ($game == 0) {
                echo "Could not find game with ID " . $game;
            } else if ($game == 1) {
                echo "Loaded game!";
            }

            break;
    }
});

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

<form action="" method="post">

    <input type="hidden" name="action" value="save" />
    <button type="submit"> Save Game </button>

</form>

<form action="" method="post">

    <input type="hidden" name="action" value="load" />
    <input type="text" name='game_id' placeholder="Game ID" />
    <button type="submit"> Load Game </button>

</form>