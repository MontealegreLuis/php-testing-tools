<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Pimple\Application\DependencyInjection;

use Adapters\Twig\Application\Templating\TwigTemplateEngine;
use Adapters\Twig\Ewallet\Extensions\EwalletExtension;
use Ewallet\Memberships\MemberFormatter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Twig's loader and environment
     */
    public function register(Container $container): void
    {
        $container[TwigTemplateEngine::class] = function () use ($container) {
            return new TwigTemplateEngine($container[Environment::class]);
        };
        $container[FilesystemLoader::class] = function () use ($container) {
            return new FilesystemLoader($container['twig']['loader_paths']);
        };
        $container[Environment::class] = function () use ($container) {
            $twig = new Environment(
                $container[FilesystemLoader::class],
                $container['twig']['options']
            );
            $twig->addExtension(new EwalletExtension(new MemberFormatter()));
            return $twig;
        };
    }
}
