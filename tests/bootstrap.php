<?php

$composerAutoloaderPath = dirname(__DIR__) . '/../../../autoload.php';
if (file_exists($composerAutoloaderPath)) {
    require_once $composerAutoloaderPath;
} else {
    // TODO: подгрузить свой Autoloader
}
