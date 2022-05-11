<?php
header('Content-Type: application/json; charset=utf-8');
define('GAME_SESSION_KEY', 'game');
require "system/db.php";
require "system/actions.php";
require "system/action_handler.php";
require "factories/board_factory.php";
require "models/player.php";
require "models/dice.php";
require "models/game.php";
require "models/json_response.php";

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
        case ACTION_GET_GAME:
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, null, null, null));
            break;
        case ACTION_ROLL:
            $json_response = $game->rollDice();
            if ($json_response->winner) {
                session_destroy();
                $game = createGame();
                $game->reset();
                $json_response->game = $game;
            }
            echo json_encode($json_response);
            break;
        case ACTION_RESET:
            session_destroy();
            $game = createGame();
            $game->reset();
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, null, null, null));
            break;
        case ACTION_SAVE:
            $gameId = $game->saveGame($db);
            session_destroy();
            $game = createGame();
            $game->reset();
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, "Game saved! Use game ID " . $gameId . " to load the game!", null, null));
            break;
        case ACTION_LOAD:
            $gameId = $game->loadGame($db, $_POST['game_id']);
            $loadRes = $gameId == 0 ? "Could not find game with ID " . $gameId : "Loaded game!";

            echo json_encode(new JsonResponse($game, null, null, null, null, null, $loadRes, null, null, null));
            break;
    }
    $_SESSION[GAME_SESSION_KEY] = $game;
});

// $game->render();
