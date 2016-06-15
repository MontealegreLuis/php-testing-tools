<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim;

use ComPHPPuebla\Slim\Resolver;
use Ewallet\Pimple\EwalletWebContainer;
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
        $container = new SlimContainer(new EwalletWebContainer($options, $this));
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
