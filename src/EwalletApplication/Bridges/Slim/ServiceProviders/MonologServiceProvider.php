<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\ServiceProviders;

use ComPHPPuebla\Slim\Resolver;
use ComPHPPuebla\Slim\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Slim;

class MonologServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     * @param array $parameters
     * @return void
     */
    public function configure(Slim $app, Resolver $resolver, array $parameters = [])
    {
        $app->container->singleton(
            'logger.slim',
            function () use ($app, $parameters) {
                $logger = new Logger($parameters['monolog']['app']['channel']);
                $logger->pushHandler(new StreamHandler(
                    $parameters['monolog']['app']['path'], Logger::DEBUG
                ));

                return $logger;
            }
        );
    }
}
