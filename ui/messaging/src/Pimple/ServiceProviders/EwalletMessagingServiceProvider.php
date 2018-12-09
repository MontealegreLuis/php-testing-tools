<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\ManageWallet\Notifications\TransferFundsEmailNotifier;
use Ewallet\Memberships\Members;
use Ewallet\Zf2\Mail\TransferFundsZendMailSender;
use Pimple\Container;
use Ports\Pimple\Application\DependencyInjection\EwalletServiceProvider;
use Ports\Twig\Application\Templating\TwigTemplateEngine;
use Twig\Loader\FilesystemLoader;
use Zend\Mail\Transport\Sendmail;

class EwalletMessagingServiceProvider extends EwalletServiceProvider
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * console command
     */
    public function register(Container $container)
    {
        parent::register($container);
        $container['ewallet.transfer_mail_sender'] = function () use ($container) {
            return new TransferFundsZendMailSender(
                $container[TwigTemplateEngine::class],
                new Sendmail()
            );
        };
        $container['ewallet.transfer_mail_notifier'] = function () use ($container) {
            return new TransferFundsEmailNotifier(
                $container[Members::class],
                $container['ewallet.transfer_mail_sender']
            );
        };
        $container->extend(
            FilesystemLoader::class,
            function (FilesystemLoader $loader) {
                $loader->addPath(__DIR__ . '/../../Resources/templates');

                return $loader;
            }
        );
    }
}
