<?php

namespace anvi\bxcreator;

use anvi\bxcreator\Command\CreateComponentCommand;
use Symfony\Component\Console\Application as ConsoleApplication;


require_once __DIR__ . '/../../autoload.php';
require_once __DIR__ . '/core/Autoloader.php';

Autoloader::addPaths([
    __DIR__ . '/core/',
    __DIR__ . '/core/Command/',
    __DIR__ . '/core/Creator/',
    __DIR__ . '/core/Configurator/',
    __DIR__ . '/core/interfaces/',
]);
Autoloader::init();

$app = Application::getInstance();
$app->run();


$consApp = new ConsoleApplication();

// TODO: add CreateComponentComand (simple и complex отдельно???)


$consApp->run();