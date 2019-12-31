<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Slim\Controllers;

use Doctrine\DataStorageSetup;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use UI\Slim\Application;

class TransferFundsControllerTest extends TestCase
{
    /** @test */
    function it_returns_an_ok_response_on_enter_transfer_information_action()
    {
        $this->container['environment'] = Environment::mock([
            'REQUEST_URI' => '/transfer-form',
        ]);

        $response = $this->app->run(true);

        $this->assertEquals(200, $response->getStatusCode(), "Actual response is: {$response->getBody()}");
    }

    /** @test */
    function it_returns_an_ok_response_on_transfer_funds_action()
    {
        $this->container['environment'] = Environment::mock([
            'REQUEST_URI' => '/transfer-funds',
            'REQUEST_METHOD' => 'POST',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ]);
        $request = Request::createFromEnvironment($this->container['environment']);
        $request = $request->withParsedBody([
            'senderId' => 'BCD',
            'recipientId' => 'EFG',
            'amount' => 2,
        ]);
        $this->container['request'] = $request;

        $response = $this->app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @before */
    public function configureApplication(): void
    {
        $options = require __DIR__ . '/../../../../config.php';
        $setup = new DataStorageSetup($options);
        $setup->updateSchema();
        $this->app = new Application($options);
        $this->container = $this->app->getContainer();
    }

    /** @var  Application */
    private $app;

    /** @var ContainerInterface */
    private $container;
}
