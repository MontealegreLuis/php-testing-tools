<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\DependencyInjection;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use UI\Slim\Controllers\ShowTransferFormController;
use UI\Slim\Controllers\TransferFundsController;

class EwalletControllerProvider implements ServiceProviderInterface
{
    /** @var App */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function register(Container $container): void
    {
        $router = $this->app->getContainer()->get('router');
        $this->app->get(
            '/',
            function (Request $request, Response $response) use ($router) {
                return $response->withRedirect($request->getUri()->withPath(
                    $router->pathFor('transfer_form')
                ));
            }
        )->setName('ewallet_home');
        $this->app->get(
            '/transfer-form',
            ShowTransferFormController::class . ':enterTransferInformation'
        )->setName('transfer_form');
        $this->app->post(
            '/transfer-funds',
            TransferFundsController::class . ':transfer'
        )->setName('transfer_funds');
    }
}
