<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use ComPHPPuebla\Slim\Resolver;
use EwalletApplication\Bridges\Pimple\EwalletWebContainer;
use EwalletApplication\Bridges\Slim\Controllers;
use EwalletApplication\Bridges\Slim\Middleware;
use Slim\Slim;

class Application extends Slim
{
    /**
     * Register all the application services, routes and middleware
     *
     * @param  array $options Associative array of application settings
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $container = new SlimContainer(new EwalletWebContainer($options));
        $this->container = $container->merge($this->container);

        $resolver = new Resolver();
        $services = new Services($resolver, $options);
        $services->configure($this);

        $controllers = new Controllers($resolver);
        $controllers->register($this);

        $middleware = new Middleware();
        $middleware->configure($this);
    }
}
