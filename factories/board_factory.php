<?php 

    require "models/tile.php";
    require "models/board.php";

    class BoardFactory{

        public function create($numberOfTiles){
            $tiles = array();

            for($i = 0; $i < $numberOfTiles; $i++){
                array_push($tiles, new Tile($i+1, Tile::$TILETYPE_NORMAL));
            }

            return new Board($tiles);
        }

    }

?>