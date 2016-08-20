<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\ManageWallet\Notifications\{TransferFundsEmailNotifier, TransferFundsNotification};
use Hexagonal\Messaging\MessageConsumer;
use stdClass;
use Symfony\Component\Console\{Command\Command, Input\InputInterface, Output\OutputInterface};

class NotifyTransferByEmailCommand extends Command
{
    /** @var TransferFundsEmailNotifier */
    private $notifier;

    /** @var MessageConsumer */
    private $consumer;

    /** @var string */
    private $exchangeName;

    /**
     * @param TransferFundsEmailNotifier $notifier
     * @param MessageConsumer $consumer
     * @param string $exchangeName
     */
    public function __construct(
        TransferFundsEmailNotifier $notifier,
        MessageConsumer $consumer,
        string $exchangeName = 'ewallet'
    ) {
        $this->notifier = $notifier;
        $this->consumer = $consumer;
        $this->exchangeName = $exchangeName;
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
        $this->consumer->open($this->exchangeName);
        $this->consumer->consume($this->exchangeName, [$this, 'notify']);
        $this->consumer->close();
    }

    /**
     * @param stdClass $message
     * @param string $event
     */
    public function notify(stdClass $message, string $event)
    {
        if (!$this->notifier->shouldNotifyOn($event)) {
            return;
        }

        $this->notifier->notify(new TransferFundsNotification(
            $message->sender_id,
            $message->amount,
            $message->recipient_id,
            $message->occurred_on
        ));
    }
}
