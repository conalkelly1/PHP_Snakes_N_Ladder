<?php



class Leaderboard
{
    public $entries;

    function __construct($db)
    {
        $stmt = $db->pdo->prepare("SELECT * FROM leaderboard");
        $stmt->execute();
        $this->entries = array();

        $results = $stmt->fetchAll();
        if (count($results)) {
            $this->createEntries($results);
        }
    }

    private function createEntries($results)
    {
        foreach ($results as &$person) {
            array_push($this->entries, $person);
        }

        $this->sortEntriesByWinLoss();
    }

    private function sortEntriesByWinLoss()
    {
        uasort($this->entries, function ($a, $b) {
            $aWinLossRatio = $a['gamesWon'] / max($a['gamesLost'], 1);
            $bWinLossRatio = $b['gamesWon'] / max($b['gamesLost'], 1);

            if ($aWinLossRatio == $bWinLossRatio) {
                return 0;
            }
            return ($aWinLossRatio > $bWinLossRatio) ? 1 : -1;
        });
    }
}
