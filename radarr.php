<?php
declare(strict_types=1);

use RadarrDaemon\Command\Maintenance;
use RadarrDaemon\Command\MovieSearch;

$container = require __DIR__ . '/app/bootstrap.php';
$app = new Silly\Application();

$app->useContainer($container, true);

$app->command('maintenance', Maintenance::class);
$app->command('moviesearch', MovieSearch::class);

$app->run();
