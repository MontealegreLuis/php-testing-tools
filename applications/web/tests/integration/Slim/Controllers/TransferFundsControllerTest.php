<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Controllers;

use Ewallet\Slim\Application;
use PHPUnit_Framework_TestCase as TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class TransferFundsControllerTest extends TestCase
{
    /** @test */
    function it_returns_an_ok_response_on_enter_transfer_information_action()
    {
        $app = new Application(require __DIR__ . '/../../../../config.tests.php');
        $app->getContainer()['environment'] = Environment::mock([
            'REQUEST_URI' => '/transfer-form',
        ]);
        $response = $app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    function it_returns_an_ok_response_on_transfer_funds_action()
    {
        $app = new Application(require __DIR__ . '/../../../../config.tests.php');
        $environment = Environment::mock([
            'REQUEST_URI' => '/transfer-funds',
            'REQUEST_METHOD' => 'POST',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ]);
        $app->getContainer()['environment'] = $environment;
        $request = Request::createFromEnvironment($environment);
        $request = $request->withParsedBody([
            'senderId' => 'BCD',
            'recipientId' => 'EFG',
            'amount' => 2,
        ]);
        $app->getContainer()['request'] = $request;
        $response = $app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
