<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{MemberId, MembersRepository};
use Symfony\Component\Console\Input\InputInterface;

/**
 * Responder implementation to transfer funds in a console application
 */
class TransferFundsConsoleResponder implements TransferFundsResponder
{
    /** @var InputInterface */
    private $input;

    /** @var MembersRepository */
    private $members;

    /** @var TransferFundsConsole */
    private $console;

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
     * @param string[] $messages
     * @param string[] $values
     */
    public function respondToInvalidTransferInput(array $messages, array $values): void
    {
        $this->console->printError($messages);
    }

    public function respondToEnterTransferInformation(MemberId $senderId): void
    {
        $this->console->printRecipients($this->members->excluding($senderId));

        $this->input->setArgument('recipientId', $this->console->promptRecipientId());
        $this->input->setArgument('amount', $this->console->promptAmountToTransfer());
    }

    public function respondToTransferCompleted(TransferFundsSummary $summary): void
    {
        $this->console->printSummary($summary);
    }
}
