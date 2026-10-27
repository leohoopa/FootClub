<?php

namespace App\enum;

enum Roles: string
{
    case ATTACK = "Attaquant";
    case MIDFIELDER = "Milieu";
    case DEFENDER = "Défenseur";
    case GOALKEEPER = "Gardien";
}
