<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim;

use UI\Slim\DependencyInjection\EwalletWebContainer;
use UI\Slim\DependencyInjection\EwalletControllerProvider;
use UI\Slim\DependencyInjection\EwalletMiddlewareProvider;
use Slim\App;

class Application extends App
{
    /**
     * Register all the application services, routes and middleware
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($container = new EwalletWebContainer($arguments));
        $container->register(new EwalletControllerProvider($this));
        $container->register(new EwalletMiddlewareProvider($this));
    }
}
