<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\EasyForms\TransferFundsForm;
use Ewallet\Slim\Controllers\TransferFundsController;
use Ewallet\Twig\RouterExtension;
use Ewallet\ManageWallet\{TransferFundsFormResponder, Web\TransferFundsWebAction};
use Ewallet\Zf2\Diactoros\DiactorosResponseFactory;
use Pimple\Container;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class EwalletWebServiceProvider extends EwalletServiceProvider
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * web interface
     */
    public function register(Container $container)
    {
        parent::register($container);

        $container['ewallet.transfer_form'] = function () {
            return new TransferFundsForm();
        };
        $container['ewallet.transfer_funds_web_responder'] = function () use ($container) {
            return new TransferFundsFormResponder(
                $container['ewallet.template_engine'],
                new DiactorosResponseFactory(),
                $container['ewallet.transfer_form'],
                $container['ewallet.members_configuration']
            );
        };
        $container['ewallet.transfer_form_controller'] = function () use ($container) {
            return new TransferFundsController(new TransferFundsWebAction(
                $container['ewallet.transfer_funds_web_responder']
            ));
        };
        $container['ewallet.transfer_funds_controller'] = function () use ($container) {
            return new TransferFundsController(
                new TransferFundsWebAction(
                    $container['ewallet.transfer_funds_web_responder'],
                    $container['ewallet.transfer_funds']
                ),
                $container['ewallet.transfer_filter_request']
            );
        };
        $container['slim.twig_extension'] = function () use ($container) {
            return new RouterExtension(
                $container['router'], $container['request']
            );
        };
        $container->extend(
            'twig.loader',
            function (Loader $loader) use ($container) {
                foreach ($container['twig']['loader_paths'] as $path) {
                    $loader->addPath($path);
                }

                return $loader;
            }
        );
        $container->extend(
            'twig.environment',
            function (Environment $twig) use ($container) {
                $twig->addExtension($container['ewallet.twig.extension']);
                $twig->addExtension($container['slim.twig_extension']);

                return $twig;
            }
        );
    }
}
