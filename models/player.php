<?php 
class Player {
    public $name;
    public $x;
    public $y;

    function __construct($name,$x,$y)
    {
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
    }
}
?>