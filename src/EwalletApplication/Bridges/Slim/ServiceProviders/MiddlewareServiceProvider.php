<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\ServiceProviders;

use ComPHPPuebla\Slim\Resolver;
use ComPHPPuebla\Slim\ServiceProvider;
use EwalletApplication\Bridges\Slim\Middleware\PersistEventsMiddleware;
use EwalletApplication\Bridges\Slim\Middleware\RequestLoggingMiddleware;
use Slim\Slim;

class MiddlewareServiceProvider implements ServiceProvider
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
            'slim.middleware.request_logging',
            function () use ($app) {
                return new RequestLoggingMiddleware(
                    $app->container->get('slim.logger')
                );
            }
        );
        $app->container->singleton(
            'slim.middleware.persist_events',
            function () use ($app) {
                return new PersistEventsMiddleware(
                    $app->container->get('ewallet.event_persist_subscriber'),
                    $app->container->get('ewallet.events_publisher')
                );
            }
        );
    }
}
