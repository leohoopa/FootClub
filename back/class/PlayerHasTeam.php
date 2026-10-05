<?php

require_once __DIR__ . '/classMember.php';
require_once __DIR__ . '/classTeam.php';

class PlayerHasTeam {
    private string $role;
    private Player $player;
    private Team $team;
    CONST ROLE_ATTACK = "Attaquant";
    CONST ROLE_MIDFIELDER = "Milieu";
    CONST ROLE_DEFENDER = "Défenseur";
    CONST ROLE_GOALKEEPER = "Gardien";

    public function __construct(string $role,Player $player, Team $team) {
        $this->role = $role;
        $this->player = $player;
        $this->team = $team;
    }

    public function getRole(): string {
        return $this->role;
    }
    public function getPlayer(): Player {
        return $this->player;
    }
    public function getTeam(): Team {
        return $this->team;
    }
    public function setRole(string $role): void {
        $this->role = $role;
    }
    public function setPlayer(Player $player): void {
        $this->player = $player;
    }
    public function setTeam(Team $team): void {
        $this->team = $team;
    }
}
