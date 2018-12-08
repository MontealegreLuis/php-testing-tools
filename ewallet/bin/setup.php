<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Setup\SetupApplication;

$configFile = getcwd() . '/setup.php';

if (!file_exists($configFile)) {
    echo 'You are missing a "setup.php" file in your project, which is required to get the database setup working';
    exit(1);
}

$application = new SetupApplication(require $configFile);
$application->run();
