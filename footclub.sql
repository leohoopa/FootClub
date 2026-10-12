DROP DATABASE IF EXISTS footclub;
CREATE DATABASE footclub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE footclub;

CREATE TABLE member (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    picture VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE player (
    member_id INT PRIMARY KEY,
    birthdate DATE NOT NULL,
    CONSTRAINT fk_player_member
        FOREIGN KEY (member_id) REFERENCES member(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE staff (
    member_id INT PRIMARY KEY,
    role VARCHAR(255) NOT NULL,
    CONSTRAINT fk_staff_member
        FOREIGN KEY (member_id) REFERENCES member(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE opposing_club (
    id INT AUTO_INCREMENT PRIMARY KEY,
    street_number VARCHAR(50) NOT NULL,
    street VARCHAR(255) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    city VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE player_has_team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    team_id INT NOT NULL,
    role VARCHAR(100) NOT NULL,
    CONSTRAINT fk_player_has_team_player
        FOREIGN KEY (player_id) REFERENCES player(member_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_player_has_team_team
        FOREIGN KEY (team_id) REFERENCES team(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    UNIQUE KEY uq_player_team_role (player_id, team_id, role)
) ENGINE=InnoDB;

CREATE TABLE match_foot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_score INT NOT NULL,
    opponent_score INT NOT NULL,
    match_date DATETIME NOT NULL,
    city VARCHAR(255) NOT NULL,
    team_id INT NOT NULL,
    opposing_club_id INT NOT NULL,
    CONSTRAINT fk_match_foot_team
        FOREIGN KEY (team_id) REFERENCES team(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_match_foot_opposing_club
        FOREIGN KEY (opposing_club_id) REFERENCES opposing_club(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX idx_match_date (match_date),
    INDEX idx_team_id (team_id),
    INDEX idx_opposing_club_id (opposing_club_id)
) ENGINE=InnoDB;

CREATE INDEX idx_member_lastname ON member(lastname);
CREATE INDEX idx_team_name ON team(name);
CREATE INDEX idx_opposing_club_city ON opposing_club(city);

SHOW TABLES;