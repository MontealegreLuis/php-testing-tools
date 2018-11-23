<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple;

use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\SymfonyConsole\Listeners\StoreEventsListener;
use Hexagonal\DomainEvents\EventPublisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EwalletConsoleContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_console_application_services()
    {
        $arguments = require __DIR__ . '/../../../config.php';
        $container = new EwalletConsoleContainer($arguments);

        $this->assertInstanceOf(
            ArgvInput::class,
            $container[InputInterface::class]
        );
        $this->assertInstanceOf(
            ConsoleOutput::class,
            $container[OutputInterface::class]
        );
        $this->assertInstanceOf(
            TransactionalTransferFundsAction::class,
            $container[TransferFundsAction::class]
        );
        $this->assertInstanceOf(
            EventPublisher::class,
            $container[EventPublisher::class]
        );
        $this->assertInstanceOf(
            EventDispatcher::class,
            $container[EventDispatcher::class]
        );
    }
}
