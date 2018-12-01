<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ApplicationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
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
