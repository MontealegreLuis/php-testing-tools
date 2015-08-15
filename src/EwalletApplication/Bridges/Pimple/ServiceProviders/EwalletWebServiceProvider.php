<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use Ewallet\Bridges\Hexagonal\Wallet\TransferFundsTransactionally;
use EwalletApplication\Bridges\Slim\Controllers\SlimController;
use EwalletModule\Bridges\Twig\Extensions\EwalletExtension;
use EwalletModule\Bridges\Zf2InputFilter\Filters\TransferFundsFilter;
use EwalletModule\Bridges\Zf2InputFilter\TransferFundsInputFilterRequest;
use EwalletModule\Controllers\TransferFundsController;
use EwalletModule\Controllers\TransferFundsResponder;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use EwalletModule\View\MemberFormatter;
use Hexagonal\Bridges\Doctrine2\Application\Services\DoctrineSession;
use Pimple\Container;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class EwalletWebServiceProvider extends EwalletConsoleServiceProvider
{
    /**
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        parent::register($pimple);

        $pimple['ewallet.transfer_form'] = function () {
            return new TransferFundsForm();
        };
        $pimple['ewallet.transfer_filter_request'] = function () use ($pimple) {
            return new TransferFundsInputFilterRequest(
                new TransferFundsFilter(),
                $pimple['ewallet.members_configuration']
            );
        };
        $pimple['ewallet.members_configuration'] = function () use ($pimple) {
            return new MembersConfiguration(
                $pimple['ewallet.member_repository']
            );
        };
        $pimple['ewallet.transfer_funds_responder'] = function () use ($pimple) {
            return new TransferFundsResponder(
                $pimple['twig.environment'],
                $pimple['ewallet.transfer_form'],
                $pimple['ewallet.members_configuration']
            );
        };
        $pimple['ewallet.transfer_form_controller'] = function () use ($pimple) {
            return new SlimController(new TransferFundsController(
                $pimple['ewallet.transfer_funds_responder']
            ));
        };
        $pimple['ewallet.transfer_funds'] = function () use ($pimple) {
            $transferFunds = new TransferFundsTransactionally(
                $pimple['ewallet.member_repository']
            );
            $transferFunds->setTransactionalSession(new DoctrineSession(
                $pimple['doctrine.em']
            ));

            return $transferFunds;
        };
        $pimple['ewallet.transfer_funds_controller'] = function () use ($pimple) {
            return new SlimController(new TransferFundsController(
                $pimple['ewallet.transfer_funds_responder'],
                $pimple['ewallet.transfer_funds']
            ));
        };
        $pimple['ewallet.twig.extension'] = function () {
            return new EwalletExtension(new MemberFormatter());
        };
        $pimple->extend(
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(__DIR__ . '/../../Slim/Resources/templates');
                $loader->addPath(
                    __DIR__ . '/../../../../EwalletModule/Bridges/Twig/Resources/views'
                );

                return $loader;
            }
        );
        $pimple->extend(
            'twig.environment',
            function (Environment $environment) use ($pimple) {
                $environment->addExtension($pimple['ewallet.twig.extension']);

                return $environment;
            }
        );
    }
}
