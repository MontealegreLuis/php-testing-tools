<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\Actions\Notifications\TransferFundsEmailNotifier;
use Ewallet\Zf2\Mail\TransferFundsZendMailSender;
use Ewallet\Zf2\Mail\TransportFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class EwalletMessagingServiceProvider extends EwalletServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * console command
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        parent::register($container);
        $container['ewallet.transfer_mail_sender'] = function () use ($container) {
            return new TransferFundsZendMailSender(
                $container['ewallet.template_engine'],
                (new TransportFactory())->buildTransport($container['mail'])
            );
        };
        $container['ewallet.transfer_mail_notifier'] = function () use ($container) {
            return new TransferFundsEmailNotifier(
                $container['ewallet.member_repository'],
                $container['ewallet.transfer_mail_sender']
            );
        };
        $container->extend(
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(__DIR__ . '/../../Resources/templates');

                return $loader;
            }
        );
        $container->extend(
            'twig.environment',
            function (Environment $twig) use ($container) {
                $twig->addExtension($container['ewallet.twig.extension']);

                return $twig;
            }
        );
    }
}
