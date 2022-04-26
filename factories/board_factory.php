<?php 

    require "models/tile.php";
    require "models/board.php";

    class BoardFactory{

        public function create($numberOfTiles){
            $tiles = array();
            $wormholeLocations = $this->getWormholeLocations($numberOfTiles,3);

            for($i = 0; $i < $numberOfTiles; $i++){
                $isWormholeLocation = in_array($i,$wormholeLocations);

                array_push($tiles, new Tile($i+1,$isWormholeLocation ? Tile::$TILETYPE_WORMHOLE : Tile::$TILETYPE_NORMAL));
            }

            return new Board($tiles);
        }
        private function getWormholeLocations($numberOfTiles, $numberOfWormholes){
            $wormholeTileNumbers = array();

            while(count($wormholeTileNumbers) < $numberOfWormholes)
            {
                $randomTileNumber = rand(1,$numberOfTiles-1);

                if(!in_array($randomTileNumber,$wormholeTileNumbers))
                {
                    array_push($wormholeTileNumbers, $randomTileNumber);
                    echo "<br> Wormhole @ ". $randomTileNumber ."<br>";
                }
            }
            return $wormholeTileNumbers;
        }

    }

?>