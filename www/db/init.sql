CREATE TABLE IF NOT EXISTS daily_stats
(
    id            INT(11)  NOT NULL AUTO_INCREMENT,
    date          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    accidents     INT(11),
    deaths        INT(11),
    child_deaths  INT(11),
    injured       INT(11),
    child_injured INT(11),
    PRIMARY KEY (id)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;

