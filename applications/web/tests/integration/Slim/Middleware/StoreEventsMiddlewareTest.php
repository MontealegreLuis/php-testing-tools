<?php
/**
 * PHP version 7.1
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

    /** @test */
    function it_persists_an_event_published_inside_an_slim_route()
    {
        $app = new App();
        $container = $app->getContainer();
        $container['environment'] = Environment::mock(['REQUEST_URI' => '/']);
        $app->add($this->middleware);

        // If you use the instance variable it will try to use the container
        // instead of the instance defined by this class
        $publisher = $this->publisher;
        $app->get('/', function($_, $response) use ($publisher) {
            $events = new SplObjectStorage();
            $events->attach(A::transferWasMadeEvent()->build());
            $publisher->publish($events);

            return $response;
        })->setName('home');

        $response = $app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $this->store->allEvents());
    }

    /** @before */
    function configureMiddleware(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');
        $this
            ->_entityManager()
            ->createQuery('DELETE FROM ' . StoredEvent::class)
            ->execute()
        ;

        /** @var \Hexagonal\Doctrine2\DomainEvents\EventStoreRepository $store */
        $this->store = $this->_repositoryForEntity(StoredEvent::class);
        $this->publisher = new EventPublisher();
        $this->middleware = new StoreEventsMiddleware(
            new PersistEventsSubscriber(
                $this->store, new StoredEventFactory(new JsonSerializer())
            ),
            $this->publisher
        );
    }

    /** @var  StoreEventsMiddleware */
    private $middleware;

    /** @var  EventPublisher */
    private $publisher;

    /** @var \Hexagonal\Doctrine2\DomainEvents\EventStoreRepository */
    private $store;
}
