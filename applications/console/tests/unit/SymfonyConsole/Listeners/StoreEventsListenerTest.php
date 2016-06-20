<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Listeners;

use Hexagonal\DomainEvents\{EventPublisher, PersistEventsSubscriber};
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StoreEventsListenerTest extends TestCase
{
    /** @test */
    function it_ignores_commands_except_the_transfer_funds_one()
    {
        $command = new Command('ewallet:any-other-command');
        $publisher = Mockery::spy(EventPublisher::class);
        $listener = new StoreEventsListener(
            Mockery::mock(PersistEventsSubscriber::class),
            $publisher
        );
        $listener->storeEvents(new ConsoleCommandEvent(
            $command,
            Mockery::mock(InputInterface::class),
            Mockery::mock(OutputInterface::class)
        ));

        $publisher->shouldNotHaveReceived('subscribe');
    }

    /** @test */
    function it_subscribes_the_store_events_listener_when_running_the_transfer_funds_command()
    {
        $command = new Command('ewallet:transfer');
        $publisher = Mockery::spy(EventPublisher::class);
        $subscriber = Mockery::mock(PersistEventsSubscriber::class);
        $listener = new StoreEventsListener($subscriber, $publisher);
        $listener->storeEvents(new ConsoleCommandEvent(
            $command,
            Mockery::mock(InputInterface::class),
            Mockery::mock(OutputInterface::class)
        ));

        $publisher
            ->shouldHaveReceived('subscribe')
            ->once()
            ->with($subscriber)
        ;
    }
}
