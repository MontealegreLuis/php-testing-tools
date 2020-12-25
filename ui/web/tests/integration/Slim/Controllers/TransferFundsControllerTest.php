<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Slim\Controllers;

use Adapters\Symfony\DependencyInjection\ContainerFactory;
use Alice\ThreeMembersWithSameBalanceFixture;
use Doctrine\DataStorageSetup;
use Framework\Slim\ApplicationFactory;
use Http\Factory\Guzzle\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Slim\App;

final class TransferFundsControllerTest extends TestCase
{
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
    public function let(): void
    {
        $options = require __DIR__ . '/../../../../config.php';
        $setup = new DataStorageSetup($options);
        $setup->updateSchema();
        $fixture = new ThreeMembersWithSameBalanceFixture($setup->entityManager());
        $fixture->load();
        $this->app = ApplicationFactory::createFromContainer(ContainerFactory::new());
    }

    private App $app;
}
