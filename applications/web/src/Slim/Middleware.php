<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;

class Middleware implements ServiceProviderInterface
{
    /** @var App */
    private $app;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param Set $container
     */
    public function register(Container $container)
    {
        $this->app->add($container['slim.middleware.request_logging']);
        $this->app->add($container['slim.middleware.store_events']);
    }
}
