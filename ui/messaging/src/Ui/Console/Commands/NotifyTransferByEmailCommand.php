<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Application\Messaging\MessageConsumer;
use Closure;
use Ewallet\ManageWallet\Notifications\TransferFundsEmailNotifier;
use Ewallet\ManageWallet\Notifications\TransferFundsNotification;
use stdClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class NotifyTransferByEmailCommand extends Command
{
    private TransferFundsEmailNotifier $notifier;

    private MessageConsumer $consumer;

    private string $exchangeName;

    public function __construct(
        TransferFundsEmailNotifier $notifier,
        MessageConsumer $consumer,
        string $exchangeName = 'ewallet'
    ) {
        parent::__construct();
        $this->notifier = $notifier;
        $this->consumer = $consumer;
        $this->exchangeName = $exchangeName;
    }

    protected function configure(): void
    {
        $this
            ->setName('ewallet:transfer:email')
            ->setDescription('Notify by email that a fund transfer was completed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->consumer->open($this->exchangeName);
        $this->consumer->consume($this->exchangeName, Closure::fromCallable(function (stdClass $message, string $event) : void {
            $this->notify($message, $event);
        }));
        $this->consumer->close();

        return 0;
    }

    public function notify(stdClass $message, string $event): void
    {
        if (! $this->notifier->shouldNotifyOn($event)) {
            return;
        }

        $this->notifier->notify(new TransferFundsNotification(
            $message->sender_id,
            (int) $message->amount,
            $message->recipient_id
        ));
    }
}
