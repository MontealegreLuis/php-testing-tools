<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class TwigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['twig.loader'] = function () use ($pimple) {
            return new Loader($pimple['twig']['loader_paths']);
        };
        $pimple['twig.environment'] = function () use ($pimple) {
            return new Environment(
                $pimple['twig.loader'],
                $pimple['twig']['options']
            );
        };
    }
}
