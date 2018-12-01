<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Application\DependencyInjection\EwalletServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\MemberFormatter;
use Ewallet\Memberships\MembersWebRepository;
use Ewallet\Slim\Controllers\ShowTransferFormController;
use Ewallet\Slim\Controllers\TransferFundsController;
use Ewallet\Twig\Extensions\EwalletExtension;
use Ewallet\Twig\RouterExtension;
use Ewallet\Twig\TwigTemplateEngine;
use Pimple\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class EwalletWebServiceProvider extends EwalletServiceProvider
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * web interface
     */
    public function register(Container $container)
    {
        parent::register($container);
        $container[ShowTransferFormController::class] = function () use ($container) {
            return new ShowTransferFormController(
                new MembersWebRepository($container[EntityManagerInterface::class]),
                $container[TwigTemplateEngine::class]
            );
        };
        $container[TwigTemplateEngine::class] = function () use ($container) {
            return new TwigTemplateEngine($container[Environment::class]);
        };
        $container[TransferFundsController::class] = function () use ($container) {
            return new TransferFundsController(
                $container[TransferFundsAction::class],
                $container[TwigTemplateEngine::class],
                new MembersWebRepository($container[EntityManagerInterface::class])
            );
        };
        $container->extend(
            FilesystemLoader::class,
            function (FilesystemLoader $loader) use ($container) {
                foreach ($container['twig']['loader_paths'] as $path) {
                    $loader->addPath($path);
                }

                return $loader;
            }
        );
        $container->extend(
            Environment::class,
            function (Environment $twig) use ($container) {
                $twig->addExtension($container[EwalletExtension::class]);
                $twig->addExtension($container[RouterExtension::class]);

                return $twig;
            }
        );
        $container[EwalletExtension::class] = function () {
            return new EwalletExtension(new MemberFormatter());
        };
        $container[RouterExtension::class] = function () use ($container) {
            return new RouterExtension($container['router'], $container['request']);
        };
    }
}
