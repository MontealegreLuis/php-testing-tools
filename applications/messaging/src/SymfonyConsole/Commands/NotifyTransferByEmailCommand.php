<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Actions\Notifications\TransferFundsEmailNotifier;
use Ewallet\Actions\Notifications\TransferFundsNotification;
use Hexagonal\Messaging\MessageConsumer;
use stdClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyTransferByEmailCommand extends Command
{
    /** @var TransferFundsEmailNotifier */
    private $notifier;

    /** @var MessageConsumer */
    private $consumer;

    /**
     * @param TransferFundsEmailNotifier $notifier
     * @param MessageConsumer $consumer
     */
    public function __construct(
        TransferFundsEmailNotifier $notifier,
        MessageConsumer $consumer
    ) {
        $this->notifier = $notifier;
        $this->consumer = $consumer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('ewallet:transfer:email')
            ->setDescription('Notify by email that a fund transfer was completed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->consumer->open('ewallet');
        $this->consumer->consume('ewallet', [$this, 'notify']);
        $this->consumer->close();
    }

    /**
     * @param stdClass $message
     * @param string $event
     */
    public function notify(stdClass $message, $event)
    {
        if (!$this->notifier->shouldNotifyOn($event)) {
            return;
        }

        $this->notifier->notify(new TransferFundsNotification(
            $message->from_member_id,
            $message->amount,
            $message->to_member_id,
            $message->occurred_on
        ));
    }
}
