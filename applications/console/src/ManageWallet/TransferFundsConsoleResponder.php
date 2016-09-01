<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{MemberId, MembersRepository};
use Symfony\Component\Console\Input\InputInterface;

class TransferFundsConsoleResponder implements TransferFundsResponder
{
    /** @var InputInterface */
    private $input;

    /** @var MembersRepository */
    private $members;

    /** @var TransferFundsConsole */
    private $console;

    /**
     * @param InputInterface $input
     * @param MembersRepository $members
     * @param TransferFundsConsole $console
     */
    public function __construct(
        InputInterface $input,
        MembersRepository $members,
        TransferFundsConsole $console
    ) {
        $this->input = $input;
        $this->members = $members;
        $this->console = $console;
    }

    /**
     * @param array $messages
     * @param array $values
     */
    public function respondToInvalidTransferInput(array $messages, array $values)
    {
        $this->console->printError($messages);
    }

    /**
     * @param MemberId $senderId
     */
    public function respondToEnterTransferInformation(MemberId $senderId)
    {
        $this->console->printRecipients($this->members->excluding($senderId));

        $this->input->setArgument(
            'recipientId',
            $this->console->promptRecipientId()
        );
        $this->input->setArgument(
            'amount',
            $this->console->promptAmountToTransfer()
        );
    }

    /**
     * @param TransferFundsSummary $summary
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary)
    {
        $this->console->printSummary($summary);
    }
}
