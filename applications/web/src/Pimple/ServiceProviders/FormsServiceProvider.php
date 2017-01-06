<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use EasyForms\Bridges\Twig\{FormExtension, FormRenderer, FormTheme};
use Pimple\{Container, ServiceProviderInterface};
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

class FormsServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the services required to render forms for the web application
     */
    public function register(Container $container)
    {
        $container->extend(
            'twig.environment',
            function (Environment $twig) use ($container) {
                $renderer = new FormRenderer(
                    new FormTheme($twig, $container['forms']['theme'])
                );
                $twig->addExtension(new FormExtension($renderer));

                return $twig;
            }
        );
        $container->extend(
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(
                    __DIR__ . '/../../../vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig'
                );

                return $loader;
            }
        );
    }
}
