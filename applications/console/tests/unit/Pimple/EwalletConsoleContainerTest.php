<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple;

use Dotenv\Dotenv;
use Ewallet\Actions\TransferFundsAction;
use Ewallet\Responders\TransferFundsConsoleResponder;
use Ewallet\SymfonyConsole\Listeners\StoreEventsListener;
use Hexagonal\DomainEvents\EventPublisher;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\{Input\ArgvInput, Output\ConsoleOutput};
use Symfony\Component\EventDispatcher\EventDispatcher;

class EwalletConsoleContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_console_application_services()
    {
        (new Dotenv(__DIR__ . '/../../../', '.env.tests'))->load();
        $options = require __DIR__ . '/../../../config.tests.php';
        $container = new EwalletConsoleContainer($options);

        $this->assertInstanceOf(
            ArgvInput::class,
            $container['ewallet.console_input']
        );
        $this->assertInstanceOf(
            ConsoleOutput::class,
            $container['ewallet.console_output']
        );
        $this->assertInstanceOf(
            TransferFundsConsoleResponder::class,
            $container['ewallet.transfer_funds_console_responder']
        );
        $this->assertInstanceOf(
            TransferFundsAction::class,
            $container['ewallet.transfer_funds_console_action']
        );
        $this->assertInstanceOf(
            EventPublisher::class,
            $container['ewallet.events_publisher']
        );
        $this->assertInstanceOf(
            StoreEventsListener::class,
            $container['ewallet.store_events_listener']
        );
        $this->assertInstanceOf(
            EventDispatcher::class,
            $container['ewallet.console.dispatcher']
        );
    }
}
