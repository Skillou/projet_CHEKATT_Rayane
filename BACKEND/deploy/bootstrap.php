<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$paths = array("/src");
$isDevMode = false;

$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);

$dbParams = array(
    'driver' => 'pdo_pgsql',
    'user' => 'cnam_d2ol_user',
    'password' => 'ED8pWZBuwIWfwsrg8dArpBNzuX3eSgRZ',
    'dbname' => 'db_skillou',
    'host' => 'dpg-cdr2k8ta4991vasbae90-a.frankfurt-postgres.render.com',
    'port' => '5432',
    'sslmode' => 'require',
);

// $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);

$entityManager = EntityManager::create($dbParams, $config);
