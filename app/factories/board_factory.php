<?php 

    require "models/tile.php";
    require "models/board.php";

    class BoardFactory{

        public function create($numberOfTiles){
            $tiles = array();
            $wormholeLocations = $this->getWormholeLocations($numberOfTiles,3);
            $blackholeLocations = $this->getBlackholeLocations($numberOfTiles,2);

            for($i = 0; $i < $numberOfTiles; $i++){
                $isWormholeLocation = in_array($i,$wormholeLocations);
                $isBlackholeLocation = in_array($i,$blackholeLocations);

                array_push($tiles, new Tile($i+1,$isWormholeLocation ? Tile::$TILETYPE_WORMHOLE : ($isBlackholeLocation ? Tile::$TILETYPE_BLACKHOLE : Tile::$TILETYPE_NORMAL)));
            }

            return new Board($tiles);
        }
        private function getWormholeLocations($numberOfTiles, $numberOfWormholes){
            $wormholeTileNumbers = array();

            while(count($wormholeTileNumbers) < $numberOfWormholes)
            {
                $randomTileNumber = rand(1,$numberOfTiles-2);

                if(!in_array($randomTileNumber,$wormholeTileNumbers))
                {
                    array_push($wormholeTileNumbers, $randomTileNumber);
                    // echo "<br> Wormhole @ ". $randomTileNumber ."<br>";
                }
            }
            return $wormholeTileNumbers;
        }
        private function getBlackholeLocations($numberOfTiles, $numberOfBlackholes){
            $blackholeTileNumbers = array();

            while(count($blackholeTileNumbers) < $numberOfBlackholes)
            {
                $randomTileNumber = rand(1,$numberOfTiles-2);
                
                if(!in_array($randomTileNumber, $blackholeTileNumbers))
                {
                    array_push($blackholeTileNumbers,$randomTileNumber);
                    // echo "<br> Blackhole @ ". $randomTileNumber ."<br>";

                }
                

            }
            return $blackholeTileNumbers;
        }

    }
