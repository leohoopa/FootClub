<?php

use App\Model\db\PlayerData;

require_once __DIR__ . '/../../autoloader.php';

// Test DataBase

$playerData = new PlayerData();
$playerData->insertPlayer('Martin', 'Plus', '', new DateTime('2000-01-01'));