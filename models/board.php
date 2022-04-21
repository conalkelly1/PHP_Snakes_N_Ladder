<?php 

class Board {

    public $tiles;

    function __construct($tiles)
    {
        $this->tiles = $tiles;
    }

    function renderBoard($playerOne)
    {
        $lengthOfSide = sqrt(count($this->tiles));
        for($i=0; $i<$lengthOfSide; $i++){
         for($j=0; $j<$lengthOfSide; $j++){
        ?>
            <div style="border:2px solid black; padding:12px; display:inline-flex; height:72px; width:72px; text-align:center; font-family:monospace; align-items:center; justify-content:center">
            <?php
             
             if($i == $playerOne->y && $j == $playerOne->x)
             {
                 echo  $playerOne->name;
             }
             ?>
            <?= "<br />". $this->tiles[$i*$lengthOfSide+$j]->number ?>
        </div>
        <?php
        if($j == $lengthOfSide-1){
            echo "<br />";
        }
    }
}
    }
}
/*$test = new Board();
array_push($test->tiles, new Tile());
print_r($test->tiles);*/
    ?>