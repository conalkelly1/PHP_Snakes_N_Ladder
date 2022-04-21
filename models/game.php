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

    function rollDice($player)
    {
        $diceRollValue = $this->dice->rollDice();
        $player->x += $diceRollValue;

        $rowsToIncrease = intdiv($player->x, 6);
        $player->x = $player->x % 6;
        $player->y += $rowsToIncrease;

        echo "dice roll was " . $diceRollValue;
    }

    function reset()
    {
        $this->playerOne = new Player("Player1", 0, 0);
        $this->playerTwo = new Player("Player2", 0, 0);
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
}
