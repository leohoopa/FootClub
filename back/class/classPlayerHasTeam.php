<?php
require_once __DIR__ . '/classMember.php';
require_once __DIR__ . '/classTeam.php';

class PlayerHasTeam {
    private string $role;
    private Player $player;
    private Team $team;

    public function __construct(string $role,Player $player, Team $team) {
        $this->role = $role;
        $this->player = $player;
        $this->team = $team;
    }
}