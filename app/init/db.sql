USE game;

CREATE TABLE IF NOT EXISTS game (
    id int(11) AUTO_INCREMENT NOT NULL,
    game_data LONGTEXT NOT NULL,
    created timestamp DEFAULT CURRENT_TIMESTAMP,
    lastModified timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

ALTER TABLE game ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS leaderboard (
    id int(11) AUTO_INCREMENT NOT NULL,
    player varchar(255) NOT NULL,
    gamesPlayed int(11) DEFAULT 0,
    gamesWon int(11) DEFAULT 0,
    gamesLost int(11) DEFAULT 0,
    quickestWin int(11) DEFAULT 0,
    PRIMARY KEY (id)
);

ALTER TABLE leaderboard ENGINE=InnoDB;
