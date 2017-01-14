<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Controllers;

use Ewallet\Slim\Application;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class TransferFundsControllerTest extends TestCase
{
    /** @test */
    function it_returns_an_ok_response_on_enter_transfer_information_action()
    {
        $this->container['environment'] = Environment::mock([
            'REQUEST_URI' => '/transfer-form',
        ]);

        $response = $this->app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
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
    public function configureApplication()
    {
        $this->app = new Application(require __DIR__ . '/../../../../config.php');
        $this->container = $this->app->getContainer();
    }

    /** @var  Application */
    private $app;

    /** @var ContainerInterface */
    private $container;
}
