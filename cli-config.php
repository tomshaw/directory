<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Jgut\Slim\Doctrine\EntityManagerBuilder;

$settings = include 'app/settings.php';
$settings = $settings['settings']['doctrine'];

$entityManager = EntityManagerBuilder::build($settings);

$helperSet = ConsoleRunner::createHelperSet($entityManager);

$cli = ConsoleRunner::createApplication($helperSet, [
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
]);

return $cli->run();
