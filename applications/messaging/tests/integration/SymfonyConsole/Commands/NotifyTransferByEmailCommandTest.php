<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use DateTime;
use Ewallet\Accounts\TransferWasMade;
use Ewallet\Wallet\Notifications\{TransferFundsEmailNotifier, TransferFundsNotification};
use Hexagonal\DataBuilders\A;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\RabbitMq\{AmqpMessageConsumer, ChannelConfiguration, ConfiguresMessaging};
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class NotifyTransferByEmailCommandTest extends TestCase
{
    use ConfiguresMessaging;

    /** @var AmqpMessageConsumer */
    private $consumer;

    /** @before */
    function configureChannel()
    {
        $configuration = new ChannelConfiguration();
        $configuration->temporary();
        $this->bindChannel($configuration);
        $this->consumer = new AmqpMessageConsumer($this->connection(), $configuration);
    }

    /** @test */
    function it_notifies_when_a_transfer_is_completed()
    {
        $notifier = Mockery::mock(TransferFundsEmailNotifier::class);
        $notifier
            ->shouldReceive('shouldNotifyOn')
            ->once()
            ->with(TransferWasMade::class)
            ->andReturn(true)
        ;
        $notifier
            ->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(TransferFundsNotification::class))
        ;
        $this->publish(A::storedEvent()->build());

        $tester = new CommandTester(new NotifyTransferByEmailCommand(
            $notifier, $this->consumer, $this->EXCHANGE_NAME
        ));
        $tester->execute([]);

        $this->assertEquals(0, $tester->getStatusCode());
    }

    /** @test */
    function it_ignores_events_different_to_transfer_completed()
    {
        $notifier = Mockery::mock(TransferFundsEmailNotifier::class);
        $notifier
            ->shouldReceive('shouldNotifyOn')
            ->once()
            ->with('Ewallet\UnkownEvent')
            ->andReturn(false)
        ;
        $notifier->shouldNotReceive('notify');
        $this->publish(new StoredEvent(
            '{"foo": "Unknown"}', 'Ewallet\UnkownEvent', new DateTime('now')
        ));

        $tester = new CommandTester(
            new NotifyTransferByEmailCommand($notifier, $this->consumer, 'test')
        );
        $tester->execute([]);

        $this->assertEquals(0, $tester->getStatusCode());
    }
}
