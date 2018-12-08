<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\DependencyInjection;

use UI\Slim\Middleware\RequestLoggingMiddleware;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;

class EwalletMiddlewareProvider implements ServiceProviderInterface
{
    /** @var App */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function register(Container $container)
    {
        $this->app->add($container[RequestLoggingMiddleware::class]);
    }
}
