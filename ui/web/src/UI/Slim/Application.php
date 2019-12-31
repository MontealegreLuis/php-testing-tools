<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim;

use Slim\App;
use UI\Slim\DependencyInjection\EwalletControllerProvider;
use UI\Slim\DependencyInjection\EwalletMiddlewareProvider;
use UI\Slim\DependencyInjection\EwalletWebContainer;

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
