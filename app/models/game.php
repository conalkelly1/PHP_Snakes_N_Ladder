<?php

class Game
{

    public $playerOne;
    public $playerTwo;
    public $board;
    public $dice;
    public $currentTurn;
    public $currentRound;
    public $state;
    public $winner;

    public static $STATE_IN_PROGRESS = 1;
    public static $STATE_FINISHED = 2;

    public static $PLAYER_ONE_TURN = 1;
    public static $PLAYER_TWO_TURN = 2;

    function __construct($playerOne, $playerTwo, $board, $dice, $currentTurn, $currentRound, $state)
    {
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
        $this->board = $board;
        $this->dice = $dice;
        $this->currentTurn = $currentTurn;
        $this->currentRound = $currentRound;
        $this->state = $state;
    }

    function rollDice()
    {
        $activePlayer = $this->currentTurn == Game::$PLAYER_ONE_TURN ? $this->playerOne : $this->playerTwo;

        $json_response = $this->_rollDice($activePlayer);
        $winner = $this->checkWin($activePlayer);

        $this->currentTurn = $this->currentTurn == Game::$PLAYER_ONE_TURN ? Game::$PLAYER_TWO_TURN : Game::$PLAYER_ONE_TURN;

        $json_response->game = $this;
        $json_response->winner = $winner;
        return $json_response;
    }

    private function _rollDice($player)
    {
        $diceRollValue = $this->dice->rollDice();
        // echo "dice roll was " . $diceRollValue . "<br>";
        if ($player->y == 5 && $player->x + $diceRollValue > 5) {
            // echo "BUSTED - Lose a Turn";
            return new JsonResponse($this, $diceRollValue, null, null, null, null, null, null, true, null);
        }

        $player->x += $diceRollValue;

        $rowsToIncrease = intdiv($player->x, 6);
        $player->x = $player->x % 6;
        $player->y += $rowsToIncrease;

        $currentTile = $this->getPlayersCurrentTile($player);

        $wormholePosition = null;
        $wormholeExitPosition = null;
        $blackholePosition = null;
        $blackholeExitPosition = null;
        if ($currentTile->type == Tile::$TILETYPE_WORMHOLE) {
            $finalWormholePosition = rand($currentTile->number + 1, count($this->board->tiles) - 1) - 1;
            $player->x = $finalWormholePosition % 6;
            $player->y =  intdiv($finalWormholePosition, 6);
            $wormholePosition = $currentTile->number;
            $wormholeExitPosition = $finalWormholePosition;
            // echo "You found a wormhole! @ " . $currentTile->number . " exiting at " . $finalWormholePosition + 1;
        } elseif ($currentTile->type == Tile::$TILETYPE_BLACKHOLE) {
            $finalBlackholePosition = rand(0, $currentTile->number - 1);
            $player->x = $finalBlackholePosition % 6;
            $player->y =  intdiv($finalBlackholePosition, 6);
            $blackholePosition = $currentTile->number;
            $blackholeExitPosition = $finalBlackholePosition;
            // echo "You found a Blackhole! @ " . $currentTile->number . " exiting at " . $finalBlackholePosition + 1;
        }
        return new JsonResponse($this, $diceRollValue, $wormholePosition, $wormholeExitPosition, $blackholePosition, $blackholeExitPosition, null, null, null, null);
    }

    private function getPlayersCurrentTile($player)
    {
        $lengthOfSide = sqrt(count($this->board->tiles));
        $tileIndex = $player->y * $lengthOfSide + $player->x;
        return $this->board->tiles[$tileIndex];
    }

    function checkWin($player)
    {
        if ($player->x == 5 && $player->y == 5) {
            return $player->name;
            // echo "<h1>" . $player->name . " wins!</h1>";
            $this->reset();
        }
    }

    function reset()
    {
        $this->playerOne = new Player("Player1", 0, 0);
        $this->playerTwo = new Player("Player2", 0, 0);
        $this->currentTurn = Game::$PLAYER_ONE_TURN;
    }

    function render()
    {
        $this->board->renderBoard($this->playerOne, $this->playerTwo);

?>
        <script type="text/javascript">
            const gameBoards = document.getElementsByClassName("game_board");
            if (gameBoards && gameBoards.length > 1) {
                gameBoards[0].remove();
            }
        </script>
<?php
    }

    function saveGame($db)
    {
        $serializedGame = serialize($this);
        $stmt = $db->pdo->prepare("INSERT INTO game(game_data) VALUES(:game)");
        $stmt->execute([':game' => $serializedGame]);
        return $db->pdo->lastInsertId();
    }

    function loadGame($db, $gameId)
    {
        $stmt = $db->pdo->prepare("SELECT * FROM game WHERE id=:id");
        $stmt->execute([':id' => $gameId]);

        $dbResult = $stmt->fetchAll();

        if (count($dbResult) == 0) {
            return 0;
        }

        $loadedGame = unserialize($dbResult[0]['game_data']);
        $this->playerOne = $loadedGame->playerOne;
        $this->playerTwo = $loadedGame->playerTwo;
        $this->board = $loadedGame->board;
        $this->dice = $loadedGame->dice;
        $this->currentTurn = $loadedGame->currentTurn;
        $this->currentRound = $loadedGame->currentRound;
        $this->state = $loadedGame->state;
        $this->winner = $loadedGame->winner;

        return 1;
    }
}
