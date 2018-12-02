<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class TwigServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Twig's loader and environment
     */
    public function register(Container $container)
    {
        $container[FilesystemLoader::class] = function () use ($container) {
            return new FilesystemLoader($container['twig']['loader_paths']);
        };
        $container[Environment::class] = function () use ($container) {
            return new Environment(
                $container[FilesystemLoader::class],
                $container['twig']['options']
            );
        };
    }
}
