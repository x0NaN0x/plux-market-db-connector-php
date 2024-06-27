<?php

declare(strict_types=1);

// Load queries from other files
$itemQueries = require __DIR__ . '/ItemQueries.php';
$settingsQueries = require __DIR__ . '/SettingsQueries.php';
$statisticQueries = require __DIR__ . '/StatisticQueries.php';
$storeQueries = require __DIR__ . '/StoreQueries.php';

return array_merge($itemQueries, $settingsQueries, $statisticQueries, $storeQueries);