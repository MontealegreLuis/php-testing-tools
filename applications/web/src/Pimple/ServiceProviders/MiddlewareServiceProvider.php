<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Doctrine\ORM\EntityManagerInterface;
use Ewallet\Slim\Middleware\RequestLoggingMiddleware;
use Ewallet\Slim\Middleware\StoreEventsMiddleware;
use Hexagonal\Doctrine2\DomainEvents\EventStoreRepository;
use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\DomainEvents\StoredEventFactory;
use Hexagonal\JmsSerializer\JsonSerializer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MiddlewareServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['slim.middleware.request_logging'] = function () use ($container) {
            return new RequestLoggingMiddleware($container['slim.logger']);
        };
        $container['slim.middleware.store_events'] =  function () use ($container) {
            return new StoreEventsMiddleware(
                $container[PersistEventsSubscriber::class],
                $container[EventPublisher::class]
            );
        };
        $container[EventPublisher::class] = function () use ($container) {
            $publisher = new EventPublisher();
//            $publisher->subscribe($container[LoggerInterface::class]);

            return $publisher;
        };
        $container[PersistEventsSubscriber::class] = function () use ($container) {
            return new PersistEventsSubscriber(
                $container[EventStoreRepository::class],
                new StoredEventFactory(new JsonSerializer())
            );
        };
        $container[EventStoreRepository::class] = function () use ($container) {
            return $container[EntityManagerInterface::class]->getRepository(StoredEvent::class);
        };
    }
}
