<?php

use App\Model\db\PlayerData;
use App\Model\Player;

require_once __DIR__ . '/../../autoloader.php';

// Test DataBase

$playerData = new PlayerData();
$playerData->insertPlayer(new Player('Martin', 'Plus', '', new DateTime('2000-01-01')));