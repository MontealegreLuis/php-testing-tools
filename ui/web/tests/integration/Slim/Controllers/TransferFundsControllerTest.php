<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Slim\Controllers;

use Alice\ThreeMembersWithSameBalanceFixture;
use Doctrine\WithDatabaseSetup;
use Framework\Slim\ApplicationFactory;
use Http\Factory\Guzzle\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Slim\App;
use SplFileInfo;

final class TransferFundsControllerTest extends TestCase
{
    use WithDatabaseSetup;

    /** @test */
    function it_returns_an_ok_response_on_enter_transfer_information_action()
    {
        $response = $this->app->handle((new ServerRequestFactory())->createServerRequest('GET', '/transfer-form'));

        $this->assertEquals(200, $response->getStatusCode(), "Actual response is: {$response->getBody()}");
    }

    /** @test */
    function it_returns_an_ok_response_on_transfer_funds_action()
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createServerRequest('POST', '/transfer-funds');
        $request = $request->withParsedBody([
            'senderId' => 'ABC',
            'recipientId' => 'LMN',
            'amount' => 2,
        ]);

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @before */
    public function let()
    {
        $this->_setupDatabaseSchema(new SplFileInfo(__DIR__ . '/../../../../'));
        $fixture = new ThreeMembersWithSameBalanceFixture($this->setup->entityManager());
        $fixture->load();
        $this->app = ApplicationFactory::createFromContainer($this->container);
    }

    private App $app;
}
