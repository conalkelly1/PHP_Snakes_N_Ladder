<?php
class JsonResponse
{
    public $game;
    public $diceRollValue;
    public $wormholeFoundTile;
    public $wormholeExitTile;
    public $blackholeFoundTile;
    public $blackholeExitTile;
    public $loadGameResponse;
    public $saveGameResponse;
    public $didPlayerBust;
    public $winner;
    public $leaderboard;

    function __construct(
        $game,
        $diceRollValue,
        $wormholeFoundTile,
        $wormholeExitTile,
        $blackholeFoundTile,
        $blackholeExitTile,
        $loadGameResponse,
        $saveGameResponse,
        $didPlayerBust,
        $winner,
        $leaderboard
    ) {
        $this->game = $game;
        $this->diceRollValue = $diceRollValue;
        $this->wormholeFoundTile = $wormholeFoundTile;
        $this->blackholeFoundTile = $blackholeFoundTile;
        $this->wormholeExitTile = $wormholeExitTile;
        $this->blackholeExitTile = $blackholeExitTile;
        $this->loadGameResponse = $loadGameResponse;
        $this->saveGameResponse = $saveGameResponse;
        $this->didPlayerBust = $didPlayerBust;
        $this->winner = $winner;
        $this->leaderboard = $leaderboard;
    }
}
