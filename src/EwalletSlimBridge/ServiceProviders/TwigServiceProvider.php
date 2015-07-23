<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletSlimBridge\ServiceProviders;

use ComPHPPuebla\Slim\Resolver;
use ComPHPPuebla\Slim\ServiceProvider;
use Slim\Slim;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class TwigServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     * @param array $options
     */
    public function configure(Slim $app, Resolver $resolver, array $options = [])
    {
        $app->container->singleton('twig.loader', function () use ($options) {
            return new Loader($options['twig']['loader_paths']);
        });
        $app->container->singleton(
            'twig.environment',
            function () use ($app, $options) {
                return new Environment(
                    $app->container->get('twig.loader'),
                    $options['twig']['options']
                );
            }
        );
    }
}
