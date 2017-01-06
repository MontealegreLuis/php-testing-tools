<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Providers;

use Pimple\{Container, ServiceProviderInterface};
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
        $this->app->add($container['slim.middleware.request_logging']);
        $this->app->add($container['slim.middleware.store_events']);
    }
}
