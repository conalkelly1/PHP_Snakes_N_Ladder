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
require "models/leaderboard.php";

/**
 * SESSION will have shape of
 * SESSION -> Game
 * Game -> Player 1 etc
 */
session_start();

$db = new DB();

function createNewGame($playerOne = 'Player1', $playerTwo = 'Player2', $gameState = null)
{
    $dice = new Dice(6);
    $gameState = $gameState == null ? Game::$STATE_NOT_STARTED : $gameState;
    $boardFactory = new BoardFactory();
    $board = $boardFactory->create(36);
    return new Game(new Player($playerOne, 0, 0), new Player($playerTwo, 0, 0), $board, $dice, Game::$PLAYER_ONE_TURN, 1, $gameState);
}

function loadGameFromSession()
{
    $game = null;
    if (!isset($_SESSION[GAME_SESSION_KEY])) {
        $game = createNewGame();
    } else {
        $game = $_SESSION[GAME_SESSION_KEY];
    }
    return $game;
}

function cleanReset($game)
{
    session_destroy();
    unset($_SESSION[GAME_SESSION_KEY]);
    $game = createNewGame();
    $game->reset();
    return $game;
}

$game = loadGameFromSession();

handleActions(function ($action) use ($game, $db) {
    switch ($action) {
        case ACTION_GET_GAME:
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, null, null, null, null));
            break;
        case ACTION_ROLL:
            $json_response = $game->rollDice();
            if ($json_response->winner) {
                /**
                 * Backwards logic because the turn is updated after roll, so if someone won, they won in the previous turn
                 */
                $game->addWinForPlayer($db, $game->currentTurn === GAME::$PLAYER_ONE_TURN ? $game->playerTwo : $game->playerOne);
                $game->addLossForPlayer($db, $game->currentTurn === GAME::$PLAYER_ONE_TURN ? $game->playerOne : $game->playerTwo);
                $game = cleanReset($game);
                $json_response->game = $game;
            }
            echo json_encode($json_response);
            break;
        case ACTION_RESET:
            $game = cleanReset($game);
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, null, null, null, null));
            break;
        case ACTION_SAVE:
            $gameId = $game->saveGame($db);
            $game = cleanReset($game);
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, "Game saved! Use game ID " . $gameId . " to load the game!", null, null, null));
            break;
        case ACTION_LOAD:
            $gameId = $game->loadGame($db, $_POST['game_id']);
            $loadRes = $gameId == 0 ? "Could not find game with ID " . $gameId : "Loaded game!";

            echo json_encode(new JsonResponse($game, null, null, null, null, null, $loadRes, null, null, null, null));
            break;
        case ACTION_START:
            $game = createNewGame($_POST['playerOneName'], $_POST['playerTwoName'], Game::$STATE_IN_PROGRESS);

            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, null, null, null, null));
            break;
        case ACTION_GET_LEADERBOARD:
            echo json_encode(new JsonResponse($game, null, null, null, null, null, null, null, null, null, new Leaderboard($db)));
            break;
    }
    $_SESSION[GAME_SESSION_KEY] = $game;
});

// $game->render();
