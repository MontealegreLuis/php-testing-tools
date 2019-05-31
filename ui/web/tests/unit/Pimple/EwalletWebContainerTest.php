<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Application\DomainEvents\PersistEventsSubscriber;
use PHPUnit\Framework\TestCase;
use Adapters\Doctrine\Application\DomainEvents\EventStoreRepository;
use Adapters\Twig\Application\Templating\RouterExtension;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UI\Slim\Controllers\ShowTransferFormController;
use UI\Slim\Controllers\TransferFundsController;
use UI\Slim\DependencyInjection\EwalletWebContainer;
use UI\Slim\Middleware\{RequestLoggingMiddleware};

class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_web_application_services()
    {
        $options = require __DIR__ . '/../../../config.php';
        $container = new EwalletWebContainer($options);

        $this->assertInstanceOf(
            Environment::class,
            $container[Environment::class]
        );
        $this->assertInstanceOf(
            FilesystemLoader::class,
            $container[FilesystemLoader::class]
        );
        $this->assertInstanceOf(
            ShowTransferFormController::class,
            $container[ShowTransferFormController::class]
        );
        $this->assertInstanceOf(
            TransferFundsController::class,
            $container[TransferFundsController::class]
        );
        $this->assertInstanceOf(
            RouterExtension::class,
            $container[RouterExtension::class]
        );

        $this->assertInstanceOf(
            LoggerInterface::class,
            $container['slim.logger']
        );
        $this->assertInstanceOf(
            EventStoreRepository::class,
            $container[EventStoreRepository::class]
        );
        $this->assertInstanceOf(
            PersistEventsSubscriber::class,
            $container[PersistEventsSubscriber::class]
        );
        $this->assertInstanceOf(
            RequestLoggingMiddleware::class,
            $container[RequestLoggingMiddleware::class]
        );
    }
}
