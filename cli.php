<?php

namespace anvi\bx_creator;

use anvi\bx_creator\command\CreateComponentCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

$consApp = new ConsoleApplication();

$consApp->add(new \anvi\bx_creator\command\CreateComponentCommand(__DIR__));



$consApp->run();