<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use UI\Slim\Middleware\RequestLoggingMiddleware;

class MiddlewareServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container[RequestLoggingMiddleware::class] = function () use ($container) {
            return new RequestLoggingMiddleware($container['slim.logger']);
        };
    }
}
