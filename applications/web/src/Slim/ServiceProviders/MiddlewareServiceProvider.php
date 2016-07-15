<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\ServiceProviders;

use Ewallet\Slim\Middleware\{StoreEventsMiddleware, RequestLoggingMiddleware};
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class MiddlewareServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $container['slim.middleware.request_logging'] = function () use ($container) {
            return new RequestLoggingMiddleware($container['slim.logger']);
        };
        $container['slim.middleware.store_events'] =  function () use ($container) {
            return new StoreEventsMiddleware(
                $container['ewallet.event_persist_subscriber'],
                $container['ewallet.events_publisher']
            );
        };
    }
}
