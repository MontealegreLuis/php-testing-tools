<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\ServiceProviders;

use ComPHPPuebla\Slim\Resolver;
use ComPHPPuebla\Slim\ServiceProvider;
use Hexagonal\Bridges\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\DomainEvents\StoredEventFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Slim;

class ApplicationServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     * @param array $options
     * @return void
     */
    public function configure(Slim $app, Resolver $resolver, array $options = [])
    {
        $app->container->singleton(
            'slim.logger',
            function () use ($options) {
                $logger = new Logger($options['monolog']['app']['channel']);
                $logger->pushHandler(new StreamHandler(
                    $options['monolog']['app']['path'], Logger::DEBUG
                ));

                return $logger;
            }
        );
        $app->container->singleton(
            'ewallet.event_store',
            function () use ($app) {
                return $app->container
                    ->get('doctrine.em')
                    ->getRepository(StoredEvent::class)
                ;
            }
        );
        $app->container->singleton(
            'ewallet.event_persist_subscriber',
            function () use ($app) {
                return new PersistEventsSubscriber(
                    $app->container->get('ewallet.event_store'),
                    new StoredEventFactory(new JsonSerializer())
                );
            }
        );
    }
}
