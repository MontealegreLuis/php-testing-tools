<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\SymfonyConsole\Commands;

use DataBuilders\A;
use Ewallet\ManageWallet\Notifications\{TransferFundsEmailNotifier, TransferFundsNotification};
use Ewallet\Memberships\TransferWasMade;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Ports\RabbitMq\Application\Messaging\AmqpMessageConsumer;
use Ports\RabbitMq\Application\Messaging\ChannelConfiguration;
use RabbitMq\ConfiguresMessaging;
use Symfony\Component\Console\Tester\CommandTester;

class NotifyTransferByEmailCommandTest extends TestCase
{
    use MockeryPHPUnitIntegration, ConfiguresMessaging;

    /** @test */
    function it_notifies_when_a_transfer_is_completed()
    {
        $this->notifier
            ->shouldReceive('shouldNotifyOn')
            ->once()
            ->with(TransferWasMade::class)
            ->andReturn(true)
        ;
        $this->notifier
            ->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(TransferFundsNotification::class))
        ;
        $this->publish(A::storedEvent()->build());

        $this->tester->execute([]);

        $this->assertEquals(0, $this->tester->getStatusCode());
    }

    /** @test */
    function it_ignores_events_other_than_transfer_completed()
    {
        $this->notifier
            ->shouldReceive('shouldNotifyOn')
            ->once()
            ->with('Ewallet\UnkownEvent')
            ->andReturn(false)
        ;
        $this->notifier->shouldNotReceive('notify');
        $this->publish(A::storedEvent()->withUnknownType()->build());

        $this->tester->execute([]);

        $this->assertEquals(0, $this->tester->getStatusCode());
    }

    /** @before */
    function configureChannel(): void
    {
        $configuration = ChannelConfiguration::temporary();
        $this->bindChannel($configuration);
        $consumer = new AmqpMessageConsumer($this->connection(), $configuration);
        $this->notifier = Mockery::mock(TransferFundsEmailNotifier::class);
        $this->tester = new CommandTester(new NotifyTransferByEmailCommand(
            $this->notifier, $consumer, $this->EXCHANGE_NAME
        ));
    }

    /** @var CommandTester */
    private $tester;

    /** @var TransferFundsEmailNotifier */
    private $notifier;
}
