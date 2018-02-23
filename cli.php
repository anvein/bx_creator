<?php

namespace anvi\bxcreator;

use anvi\bxcreator\command\CreateComponentCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

$consApp = new ConsoleApplication();

$consApp->add(new \anvi\bxcreator\command\CreateComponentCommand(__DIR__));



$consApp->run();