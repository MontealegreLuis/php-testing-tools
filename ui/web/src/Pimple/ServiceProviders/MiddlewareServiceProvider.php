<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\Slim\Middleware\RequestLoggingMiddleware;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MiddlewareServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[RequestLoggingMiddleware::class] = function () use ($container) {
            return new RequestLoggingMiddleware($container['slim.logger']);
        };
    }
}
