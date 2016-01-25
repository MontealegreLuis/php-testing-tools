<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Middleware;

use Ewallet\DataBuilders\A;
use Hexagonal\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\DomainEvents\StoredEventFactory;
use PHPUnit_Framework_TestCase as TestCase;
use SplObjectStorage;
use Slim\Environment;
use Slim\Slim;
use Ewallet\TestHelpers\ProvidesDoctrineSetup;

class StoreEventsMiddlewareTest extends TestCase
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
    function it_should_persist_an_event_published_inside_an_slim_route()
    {
        /** @var \Hexagonal\Doctrine2\DomainEvents\EventStoreRepository $store */
        $store = $this->entityManager->getRepository(StoredEvent::class);

        $publisher = new EventPublisher();
        $middleware = new StoreEventsMiddleware(
            new PersistEventsSubscriber(
                $store, new StoredEventFactory(new JsonSerializer())
            ),
            $publisher
        );

        $app = new Slim();
        $app->get('/', function() use ($publisher) {
            $events = new SplObjectStorage();
            $events->attach(A::transferWasMadeEvent()->build());
            $publisher->publish($events);
        });
        $app->add($middleware);
        Environment::mock([
            'REQUEST_METHOD' => 'GET',
        ]);

        $app->run();

        $this->assertCount(1, $store->allEvents());
    }
}
