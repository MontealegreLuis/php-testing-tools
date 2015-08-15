<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

class FormsServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple->extend(
            'twig.environment',
            function (Environment $twig) use ($pimple) {
                $renderer = new FormRenderer(
                    new FormTheme($twig, $pimple['forms']['theme'])
                );
                $twig->addExtension(new FormExtension($renderer));

                return $twig;
            }
        );
        $pimple->extend(
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
