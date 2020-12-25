<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Framework\Slim;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

final class ApplicationFactory
{
    public static function createFromContainer(ContainerInterface $container): App
    {
        $controllers = $container->get(RoutesProvider::class);
        $app = AppFactory::createFromContainer($container);
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);
        $controllers->addRoutes($app);

        return $app;
    }
}
