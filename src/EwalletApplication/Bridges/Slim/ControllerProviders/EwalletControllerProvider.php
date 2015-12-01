<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\ControllerProviders;

use ComPHPPuebla\Slim\ControllerProvider;
use ComPHPPuebla\Slim\Resolver;
use Ewallet\Accounts\Identifier;
use Slim\Slim;

class EwalletControllerProvider implements ControllerProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     */
    public function register(Slim $app, Resolver $resolver)
    {
        $app->get(
            '/',
            function () use ($app) {
                $app->redirectTo('transfer_form');
            }
        );
        $app->get(
            '/transfer-form',
            $resolver->resolve(
                $app,
                'ewallet.transfer_form_controller:enterTransferInformation',
                function () {
                    return [Identifier::fromString('ABC')];
                }
            )
        )->name('transfer_form');
        $app->post(
            '/transfer-funds',
            $resolver->resolve(
                $app,
                'ewallet.transfer_funds_controller:transfer',
                function () use ($app) {
                    /** @var \EwalletModule\Bridges\Zf2\InputFilter\TransferFundsInputFilterRequest $request */
                    $request = $app->container->get('ewallet.transfer_filter_request');
                    $request->populate($app->request->post());

                    return [$request];
                }
            )
        )->name('transfer_funds');
    }
}
