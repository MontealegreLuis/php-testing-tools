<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\MemberFormatter;
use Ewallet\Memberships\MembersWebRepository;
use Pimple\Container;
use Adapters\Pimple\Application\DependencyInjection\EwalletServiceProvider;
use Adapters\Twig\Application\Templating\RouterExtension;
use Adapters\Twig\Application\Templating\TwigTemplateEngine;
use Adapters\Twig\Ewallet\Extensions\EwalletExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UI\Slim\Controllers\ShowTransferFormController;
use UI\Slim\Controllers\TransferFundsController;

class EwalletWebServiceProvider extends EwalletServiceProvider
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * web interface
     */
    public function register(Container $container): void
    {
        parent::register($container);
        $container[ShowTransferFormController::class] = function () use ($container) {
            return new ShowTransferFormController(
                new MembersWebRepository($container[EntityManagerInterface::class]),
                $container[TwigTemplateEngine::class]
            );
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
