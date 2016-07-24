<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Middleware;

use Ewallet\DataBuilders\A;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Hexagonal\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\{EventPublisher, PersistEventsSubscriber, StoredEvent, StoredEventFactory};
use PHPUnit_Framework_TestCase as TestCase;
use SplObjectStorage;
use Slim\{Http\Environment, App};

class StoreEventsMiddlewareTest extends TestCase
{
    use ProvidesDoctrineSetup;

    /** @before */
    function cleanUpEvents()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.tests.php');
        $this
            ->_entityManager()
            ->createQuery('DELETE FROM ' . StoredEvent::class)
            ->execute()
        ;
    }

    /** @test */
    function it_persists_an_event_published_inside_an_slim_route()
    {
        /** @var \Hexagonal\Doctrine2\DomainEvents\EventStoreRepository $store */
        $store = $this->_entityManager()->getRepository(StoredEvent::class);

        $publisher = new EventPublisher();
        $middleware = new StoreEventsMiddleware(
            new PersistEventsSubscriber(
                $store, new StoredEventFactory(new JsonSerializer())
            ),
            $publisher
        );

        $app = new App();
        $container = $app->getContainer();
        $container['environment'] = Environment::mock([
            'REQUEST_URI' => '/',
        ]);
        $app->add($middleware);
        $app->get('/', function($_, $response) use ($publisher) {
            $events = new SplObjectStorage();
            $events->attach(A::transferWasMadeEvent()->build());
            $publisher->publish($events);

            return $response;
        })->setName('home');
        $response = $app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $store->allEvents());
    }
}
