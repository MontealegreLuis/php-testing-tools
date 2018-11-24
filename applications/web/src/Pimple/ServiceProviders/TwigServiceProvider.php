<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class TwigServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Twig's loader and environment
     */
    public function register(Container $container)
    {
        $container['twig.loader'] = function () use ($container) {
            return new Loader($container['twig']['loader_paths']);
        };
        $container['twig.environment'] = function () use ($container) {
            return new Environment(
                $container['twig.loader'],
                $container['twig']['options']
            );
        };
    }
}
