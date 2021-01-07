<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Adapters\Symfony\Application\DependencyInjection\ContainerFactory;
use Application\BasePath;
use Application\Environment;
use Ewallet\UI\Console\EwalletApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

(static function (): void {
    $basePath = new BasePath(new SplFileInfo(__DIR__ . '/../'));
    $environment = Environment::fromGlobals($basePath);
    $container = ContainerFactory::new($basePath, $environment);
    $application = EwalletApplication::fromContainer($container);

    $application->run($container->get(InputInterface::class), $container->get(OutputInterface::class));
})();
