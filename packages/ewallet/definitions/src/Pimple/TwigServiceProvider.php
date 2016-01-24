<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
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
     *
     * @param Container $container
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
