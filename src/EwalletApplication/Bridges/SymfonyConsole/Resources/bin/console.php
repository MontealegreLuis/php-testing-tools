<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../../../../../../vendor/autoload.php';

use EwalletApplication\Bridges\Pimple\EwalletConsoleContainer;
use EwalletApplication\Bridges\SymfonyConsole\EwalletApplication;

$options = require __DIR__ . '/../../../../../../app/config.php';
$container = new EwalletConsoleContainer($options);

$application = new EwalletApplication($container);
$application->run();
