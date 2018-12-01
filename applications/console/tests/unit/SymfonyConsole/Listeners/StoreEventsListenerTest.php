<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\SymfonyConsole\Listeners;

use Application\DomainEvents\EventPublisher;
use Application\DomainEvents\PersistEventsSubscriber;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StoreEventsListenerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    function it_ignores_commands_except_the_transfer_funds_one()
    {
        $ignoredCommand = new Command('ewallet:any-other-command');

        $this->listener->storeEvents(new ConsoleCommandEvent(
            $ignoredCommand,
            $this->input,
            $this->output
        ));

        $this->publisher->shouldNotHaveReceived('subscribe');
    }

    /** @test */
    function it_subscribes_the_store_events_listener_when_running_the_transfer_funds_command()
    {
        $subscribedCommand = new Command('ewallet:transfer');

        $this->listener->storeEvents(new ConsoleCommandEvent(
            $subscribedCommand,
            $this->input,
            $this->output
        ));

        $this
            ->publisher
            ->shouldHaveReceived('subscribe')
            ->once()
            ->with($this->subscriber)
        ;
    }

    /** @before */
    public function configureListener(): void
    {
        $this->input = Mockery::mock(InputInterface::class);
        $this->output = Mockery::mock(OutputInterface::class);
        $this->publisher = Mockery::spy(EventPublisher::class);
        $this->subscriber = Mockery::mock(PersistEventsSubscriber::class);
        $this->listener = new StoreEventsListener($this->subscriber, $this->publisher);
    }

    /** @var StoreEventsListener Subject under test */
    private $listener;

    /** @var EventPublisher */
    private $publisher;

    /** @var PersistEventsSubscriber */
    private $subscriber;

    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;
}
