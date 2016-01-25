<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim;

use Ewallet\Slim\Middleware\StoreEventsMiddleware;
use Ewallet\Slim\Middleware\RequestLoggingMiddleware;
use Hexagonal\Doctrine2\DomainEvents\EventStoreRepository;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;

class ContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_application_services()
    {
        $app = new Application(require __DIR__ . '/../../../config.php');

        $this->assertInstanceOf(
            LoggerInterface::class,
            $app->container->get('slim.logger')
        );
        $this->assertInstanceOf(
            EventStoreRepository::class,
            $app->container->get('ewallet.event_store')
        );
        $this->assertInstanceOf(
            PersistEventsSubscriber::class,
            $app->container->get('ewallet.event_persist_subscriber')
        );
        $this->assertInstanceOf(
            RequestLoggingMiddleware::class,
            $app->container->get('slim.middleware.request_logging')
        );
        $this->assertInstanceOf(
            StoreEventsMiddleware::class,
            $app->container->get('slim.middleware.store_events')
        );
    }
}
