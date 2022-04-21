<?php 



class Tile {

    public static $TILETYPE_NORMAL = 0;
    public static $TILETYPE_WORMHOLE = 1;
    public static $TILETYPE_BLACKHOLE = 2;
    public static $TILETYPE_FINISHED = 3;
    
    public int $number;
    public $type;

    function __construct($number, $type)
    {
        $this->number = $number;
        $this->type = $type;
    }
}
?>