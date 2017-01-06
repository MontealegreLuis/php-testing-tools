<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\Slim\Middleware\{StoreEventsMiddleware, RequestLoggingMiddleware};
use Pimple\{ServiceProviderInterface, Container};

class MiddlewareServiceProvider implements ServiceProviderInterface
{
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
