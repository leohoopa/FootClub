<?php
class MatchFoot {

    private int $teamScore;
    private int $opponentScore;
    private datetime $date;
    private string $city;

    public function __construct(int $teamScore, int $opponentScore, datetime $date, string $city) {
        $this->teamScore = $teamScore;
        $this->opponentScore = $opponentScore;
        $this->date = $date;
        $this->city = $city;
    }

    public function getTeamScore() : string {
        return $this->teamScore;
    }
    public function getOpponentScore() : string {
        return $this->opponentScore;
    }
    public function getDate() : datetime {
        $this->date;
    }
    public function getCity() : string {
        return $this->city;
    }

    public function setTeamScore(int $teamScore) : void {
        $this->teamScore = $teamScore;
    }

    public function setOpponentScore(int $opponentScore) : void {
        $this->opponentScore = $opponentScore;
    }

    public function setDate(datetime $date) : void {
        $this->date = $date;
    }

    public function setCity(string $city) : void {
        $this->city = $city;
    }
}
?>