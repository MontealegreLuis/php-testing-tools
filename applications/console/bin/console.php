<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Ewallet\Pimple\EwalletConsoleContainer;
use Ewallet\SymfonyConsole\EwalletApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$environment = new Dotenv(__DIR__ . '/../');
$environment->load();
$environment->required(['APP_ENV', 'DB_URL']);

$application = new EwalletApplication($container = new EwalletConsoleContainer(
    require __DIR__ . '/../config.php'
));

$application->run($container[InputInterface::class], $container[OutputInterface::class]);
