<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\ServiceProviders;

use ComPHPPuebla\Slim\Resolver;
use ComPHPPuebla\Slim\ServiceProvider;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use Slim\Slim;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

class FormsServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     * @param array $options
     */
    public function configure(Slim $app, Resolver $resolver, array $options = [])
    {
        $resolver->extend(
            $app,
            'twig.environment',
            function (Environment $twig) use ($options) {
                $renderer = new FormRenderer(
                    new FormTheme($twig, $options['forms']['theme'])
                );
                $twig->addExtension(new FormExtension($renderer));

                return $twig;
            }
        );
        $resolver->extend(
            $app,
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(
                    __DIR__ . '/../../../../../vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig'
                );

                return $loader;
            }
        );
    }
}
