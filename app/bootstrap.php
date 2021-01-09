<?php
/**
 * The bootstrap file creates and returns the container.
 */

use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required([
    'RADARR_ENDPOINT',
    'RADARR_APIKEY',
])->notEmpty();
$dotenv->required([
    'SEARCH_AMOUNT',
    'MAX_DOWNLOADING',
])->isInteger();
$dotenv->required([
    'EXCLUDE_FROM_IMPORT',
    'DELETE_FILES'
])->isBoolean();

$containerBuilder = new ContainerBuilder;
return $containerBuilder->build();
