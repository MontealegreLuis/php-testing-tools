<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\ServiceProviders;

use ComPHPPuebla\Slim\{Resolver, ServiceProvider};
use Monolog\{Handler\SyslogHandler, Logger};
use Slim\Slim;

class ApplicationServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     * @param array $options
     * @return void
     */
    public function configure(Slim $app, Resolver $resolver, array $options = [])
    {
        $app->container->singleton(
            'slim.logger',
            function () use ($options) {
                $logger = new Logger($options['monolog']['app']['channel']);
                $logger->pushHandler(new SyslogHandler(
                    $options['monolog']['app']['channel'],
                    LOG_USER,
                    Logger::DEBUG
                ));

                return $logger;
            }
        );
    }
}
