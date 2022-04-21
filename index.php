<?php
require "factories/board_factory.php";
require "models/player.php";
require "models/dice.php";
session_start();
$boardFactory = new BoardFactory();
$board = $boardFactory->create(36);

if(!isset($_SESSION['playerOne']))
{
    $player = new Player("Player1",0,0);
}
else
{
    $player = $_SESSION['playerOne'];
}
$dice = new Dice(6);
$board->renderBoard($player);

if(isset($_POST['action']) && $_POST['action']=="roll")
{
$diceRollValue = $dice->rollDice();
$player->x += $diceRollValue;
$rowsToIncrease = intdiv($player->x , 6);
$newX = $player->x % 6;
$player->y += $rowsToIncrease;
$player->x = $newX;
echo "dice roll was ". $diceRollValue;
$board->renderBoard($player);
}
if(isset($_POST['action']) && $_POST['action']=="reset")
{
    $player = new Player("Player1",0,0);
}
$_SESSION['playerOne'] = $player;
?>
<form action="" method="post">

<input type ="hidden" name="action" value="roll" />
<button type="submit"> Roll Dice </button>

</form>
<form action="" method="post">

<input type ="hidden" name="action" value="reset" />
<button type="submit"> Reset Game </button>

</form>

