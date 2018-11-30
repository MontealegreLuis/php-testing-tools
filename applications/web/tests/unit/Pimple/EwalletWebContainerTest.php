<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Application\DomainEvents\PersistEventsSubscriber;
use Ewallet\Slim\Controllers\ShowTransferFormController;
use Ewallet\Slim\Controllers\TransferFundsController;
use Ewallet\Slim\Middleware\{RequestLoggingMiddleware, StoreEventsMiddleware};
use Ewallet\Twig\RouterExtension;
use PHPUnit\Framework\TestCase;
use Ports\Doctrine\Application\DomainEvents\EventStoreRepository;
use Psr\Log\LoggerInterface;
use Twig_Environment as TwigEnvironment;
use Twig_Loader_Filesystem as Loader;

class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_web_application_services()
    {
        $options = require __DIR__ . '/../../../config.php';
        $container = new EwalletWebContainer($options);

        $this->assertInstanceOf(
            TwigEnvironment::class,
            $container['twig.environment']
        );
        $this->assertInstanceOf(
            Loader::class,
            $container['twig.loader']
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
            $container['slim.middleware.request_logging']
        );
        $this->assertInstanceOf(
            StoreEventsMiddleware::class,
            $container['slim.middleware.store_events']
        );
    }
}
