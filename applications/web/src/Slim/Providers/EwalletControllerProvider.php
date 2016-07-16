<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Providers;

use Pimple\{Container, ServiceProviderInterface};
use Slim\App;
use Slim\Http\{Request, Response};

class EwalletControllerProvider implements ServiceProviderInterface
{
    /** @var App */
    private $app;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $router = $this->app->getContainer()['router'];
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
            'ewallet.transfer_form_controller:enterTransferInformation'
        )->setName('transfer_form');
        $this->app->post(
            '/transfer-funds',
            'ewallet.transfer_funds_controller:transfer'
        )->setName('transfer_funds');
    }
}
