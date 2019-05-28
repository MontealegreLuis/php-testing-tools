<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ApplicationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container['slim.logger'] = function () use ($container) {
            $logger = new Logger($container['monolog']['app']['channel']);
            $logger->pushHandler(new SyslogHandler(
                $container['monolog']['app']['channel'],
                LOG_USER,
                Logger::DEBUG
            ));

            return $logger;
        };
    }
}
