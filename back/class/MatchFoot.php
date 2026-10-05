<?php

class MatchFoot {

    private int $teamScore;
    private int $opponentScore;
    private DateTime $date;
    private string $city;
    private Team $team;
    private OpposingClub $opposingClub;

    public function __construct(int $teamScore, int $opponentScore, DateTime $date, string $city, Team $team, OpposingClub $opposingClub) {
        $this->teamScore = $teamScore;
        $this->opponentScore = $opponentScore;
        $this->date = $date;
        $this->city = $city;
        $this->team = $team;
        $this->opposingClub = $opposingClub;
    }

    public function getTeamScore() : string {
        return $this->teamScore;
    }
    public function getOpponentScore() : string {
        return $this->opponentScore;
    }
    public function getDate() : DateTime {
        return $this->date;
    }
    public function getCity() : string {
        return $this->city;
    }
    public function getTeam() : Team {
        return $this->team;
    }
    public function getOpposingClub() : OpposingClub {
        return $this->opposingClub;
    }

    public function setTeamScore(int $teamScore) : void {
        $this->teamScore = $teamScore;
    }

    public function setOpponentScore(int $opponentScore) : void {
        $this->opponentScore = $opponentScore;
    }

    public function setDate(DateTime $date) : void {
        $this->date = $date;
    }

    public function setCity(string $city) : void {
        $this->city = $city;
    }
    public function setTeam(Team $team) : void {
        $this->team = $team;
    }
    public function setOpposingClub(OpposingClub $opposingClub) : void {
        $this->opposingClub = $opposingClub;
    }
}
?>
