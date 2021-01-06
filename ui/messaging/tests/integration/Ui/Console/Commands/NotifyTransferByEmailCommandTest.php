<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Adapters\RabbitMq\Application\Messaging\AmqpMessageConsumer;
use Adapters\RabbitMq\Application\Messaging\ChannelConfiguration;
use DataBuilders\A;
use Ewallet\ManageWallet\Notifications\TransferFundsEmailNotifier;
use Ewallet\ManageWallet\Notifications\TransferFundsNotification;
use Ewallet\Memberships\TransferWasMade;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
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
    function let()
    {
        $configuration = ChannelConfiguration::temporary();
        $this->bindChannel($configuration);
        $consumer = new AmqpMessageConsumer($this->connection(), $configuration);
        $this->notifier = Mockery::mock(TransferFundsEmailNotifier::class);
        $this->tester = new CommandTester(new NotifyTransferByEmailCommand(
            $this->notifier,
            $consumer,
            $this->EXCHANGE_NAME
        ));
    }

    /** @var CommandTester */
    private $tester;

    /** @var TransferFundsEmailNotifier */
    private $notifier;
}
