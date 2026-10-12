<?php

require_once __DIR__ . '/../PlayerData.php';

// Test DataBase

$playerData = new PlayerData();
$playerData->insertPlayer('Martin', 'Plus', '', new DateTime('2000-01-01'));