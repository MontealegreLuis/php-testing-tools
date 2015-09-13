<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use EwalletApplication\Bridges\Slim\Controllers\SlimController;
use EwalletApplication\Bridges\Twig\RouterExtension;
use EwalletModule\Bridges\Twig\Extensions\EwalletExtension;
use EwalletModule\Bridges\Zf2\Diactoros\DiactorosResponseFactory;
use EwalletModule\Bridges\Zf2\InputFilter\Filters\TransferFundsFilter;
use EwalletModule\Bridges\Zf2\InputFilter\TransferFundsInputFilterRequest;
use EwalletModule\Actions\TransferFundsAction;
use EwalletModule\Bridges\EasyForms\TransferFundsFormResponder;
use EwalletModule\Bridges\EasyForms\MembersConfiguration;
use EwalletModule\Bridges\EasyForms\TransferFundsForm;
use EwalletModule\View\MemberFormatter;
use Pimple\Container;
use Slim\Slim;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class EwalletWebServiceProvider extends EwalletConsoleServiceProvider
{
    /** @var  Slim */
    private $app;

    /**
     * @param Slim $app
     */
    public function __construct(Slim $app)
    {
        $this->app = $app;
    }

    /**
     * Register the services for Transfer Funds feature delivered through a
     * web interface
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        parent::register($container);

        $container['ewallet.transfer_form'] = function () {
            return new TransferFundsForm();
        };
        $container['ewallet.transfer_filter_request'] = function () use ($container) {
            return new TransferFundsInputFilterRequest(
                new TransferFundsFilter(),
                $container['ewallet.members_configuration']
            );
        };
        $container['ewallet.members_configuration'] = function () use ($container) {
            return new MembersConfiguration(
                $container['ewallet.member_repository']
            );
        };
        $container['ewallet.transfer_funds_responder'] = function () use ($container) {
            return new TransferFundsFormResponder(
                $container['ewallet.template_engine'],
                new DiactorosResponseFactory(),
                $container['ewallet.transfer_form'],
                $container['ewallet.members_configuration']
            );
        };
        $container['ewallet.transfer_form_controller'] = function () use ($container) {
            return new SlimController(new TransferFundsAction(
                $container['ewallet.transfer_funds_responder']
            ));
        };
        $container['ewallet.transfer_funds_controller'] = function () use ($container) {
            return new SlimController(new TransferFundsAction(
                $container['ewallet.transfer_funds_responder'],
                $container['ewallet.transfer_funds']
            ));
        };
        $container['ewallet.twig.extension'] = function () {
            return new EwalletExtension(new MemberFormatter());
        };
        $container['slim.twig_extension'] = function () {
            return new RouterExtension($this->app->router, $this->app->request);
        };
        $container->extend(
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(__DIR__ . '/../../Slim/Resources/templates');
                $loader->addPath(
                    __DIR__ . '/../../../../EwalletModule/Bridges/Twig/Resources/views'
                );

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
