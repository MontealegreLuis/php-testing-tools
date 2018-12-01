<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Slim;

use Ewallet\Pimple\EwalletWebContainer;
use Ewallet\Slim\Providers\EwalletControllerProvider;
use Ewallet\Slim\Providers\EwalletMiddlewareProvider;
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
