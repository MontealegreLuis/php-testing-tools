<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../../../../../../vendor/autoload.php';

use EwalletApplication\Bridges\Pimple\EwalletConsoleContainer;
use EwalletApplication\Bridges\SymfonyConsole\EwalletApplication;
use Dotenv\Dotenv;

$environment = new Dotenv(__DIR__ . '/../../../../../../');
$environment->load();
$environment->required([
    'DOCTRINE_DEV_MODE', 'TWIG_DEBUG', 'SMTP_HOST', 'SMTP_PORT'
]);

$application = new EwalletApplication($container = new EwalletConsoleContainer(
    require __DIR__ . '/../../../../../../app/config.php'
));

$application->run(
    $container['ewallet.console_input'],
    $container['ewallet.console_output']
);
