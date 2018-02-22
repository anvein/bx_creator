<?php

namespace Anvi\BitrixCreator;

use Anvi\BitrixCreator\Command\CreateSimpleComponentCommand;
use Symfony\Component\Console\Application as ConsoleApplication;


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/core/Autoloader.php';

Autoloader::addPath([
    __DIR__ . '/core/',
    __DIR__ . '/core/Command/',
    __DIR__ . '/core/interfaces/',
]);
Autoloader::init();

$app = Application::getInstance();
$app->run();

$consApp = new ConsoleApplication();
$consApp->add(new CreateSimpleComponentCommand());
// TODO: add CreateComponentComand (simple и complex отдельно???)



$consApp->run();