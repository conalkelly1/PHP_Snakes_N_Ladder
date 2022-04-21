<?php 

class Game {

    public $playerOne;
    public $playerTwo;
    public $board;
    public $dice;
    public $currentTurn;
    public $currentRound;
    public $state;
    public $winner;

    function __construct($playerOne, $playerTwo, $board, $dice, $currentTurn, $currentRound, $state, $winner)
    {
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
        $this->board = $board;
        $this->dice = $dice;
        $this->currentTurn = $currentTurn;
        $this->currentRound = $currentRound;
        $this->state = $state;
        $this->winner = $winner;
    }
    
}
?>