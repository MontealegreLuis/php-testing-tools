<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletSlimBridge\ControllerProviders;

use ComPHPPuebla\Slim\ControllerProvider;
use ComPHPPuebla\Slim\Resolver;
use Ewallet\Accounts\Identifier;
use Money\Money;
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
            '/transfer-form',
            $resolver->resolve(
                $app,
                'ewallet.transfer_form_controller:showForm',
                function () {
                    return [Identifier::fromString('ABC')];
                }
            )
        );
        $app->post(
            '/transfer-funds',
            $resolver->resolve(
                $app,
                'ewallet.transfer_funds_controller:transfer',
                function () use ($app) {
                    return [
                        Identifier::fromString($app->request->post('fromMemberId')),
                        Identifier::fromString($app->request->post('toMemberId')),
                        Money::MXN((integer) $app->request->post('amount') * 100)
                    ];
                }
            )
        );
    }
}
