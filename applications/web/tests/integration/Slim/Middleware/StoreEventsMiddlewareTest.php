<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Slim\Middleware;

use Application\DomainEvents\EventPublisher;
use Application\DomainEvents\PersistEventsSubscriber;
use Application\DomainEvents\StoredEvent;
use Application\DomainEvents\StoredEventFactory;
use DataBuilders\A;
use Doctrine\ProvidesDoctrineSetup;
use PHPUnit\Framework\TestCase;
use Ports\Doctrine\Application\DomainEvents\EventStoreRepository;
use Ports\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Slim\App;
use Slim\Http\Environment;
use SplObjectStorage;

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

        $this->store = new EventStoreRepository($this->_entityManager());
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

    /** @var \Ports\Doctrine\DomainEvents\EventStoreRepository */
    private $store;
}
