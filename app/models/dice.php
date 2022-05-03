<?php 

class Dice {

    private $sides;

    function __construct(int $sides)
    {
        $this->sides = $sides;
    }

    public function rollDice()
    {
        return random_int(1, $this->sides);
    }
}
?>