<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\Middleware;

use Ewallet\Bridges\Tests\A;
use Ewallet\Bridges\Tests\ProvidesDoctrineSetup;
use Hexagonal\Bridges\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\DomainEvents\StoredEventFactory;
use PHPUnit_Framework_TestCase as TestCase;
use SplObjectStorage;
use Slim\Environment;
use Slim\Slim;

class PersistEventsMiddlewareTest extends TestCase
{
    use ProvidesDoctrineSetup;

    /** @before */
    function cleanUpEvents()
    {
        $this->_setUpDoctrine();
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . StoredEvent::class)
            ->execute()
        ;
    }

    /** @test */
    function it_should_persist_an_event_in_published_inside_an_slim_route()
    {
        $app = new Slim();
        $store = $this->entityManager->getRepository(StoredEvent::class);
        $factory = new StoredEventFactory(new JsonSerializer());
        $publisher = new EventPublisher();
        $middleware = new PersistEventsMiddleware(
            new PersistEventsSubscriber($store, $factory),
            $publisher
        );
        $app->get('/', function() use ($publisher) {
            $events = new SplObjectStorage();
            $events->attach(A::transferWasMadeEvent()->build());
            $publisher->register($events);
            $publisher->publish();
        });
        $app->add($middleware);
        Environment::mock([
            'REQUEST_METHOD' => 'GET',
        ]);

        $app->run();

        $this->assertCount(1, $store->eventsStoredAfter());
    }
}
