<?php

namespace anvein\bx_creator;

use anvein\bx_creator\command\CreateComponentCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

$consApp = new ConsoleApplication();

$consApp->add(new \anvein\bx_creator\command\CreateComponentCommand(__DIR__));



$consApp->run();