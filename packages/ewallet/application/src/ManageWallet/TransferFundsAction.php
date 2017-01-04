<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;

/**
 * This is a two steps action. First, the member making the transfer enters the
 * information needed to perform it. Once information is provided, the transfer
 * is attempted. If the information is invalid, the member receives the
 * appropriate feedback in order to fix the errors. Otherwise the transfer
 * completes.
 */
class TransferFundsAction implements CanTransferFunds
{
    /** @var TransferFunds */
    private $command;

    /** @var TransferFundsResponder */
    protected $responder;

    public function __construct(
        TransferFundsResponder $responder,
        TransferFunds $transferFunds = null
    ) {
        $this->responder = $responder;
        $this->command = $transferFunds;
        $transferFunds && $this->command->attach($this);
    }

    public function enterTransferInformation(MemberId $senderId)
    {
        $this->responder->respondToEnterTransferInformation($senderId);
    }

    /**
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or the
     * recipient cannot be found
     * @throws \Ewallet\Memberships\InsufficientFunds If the sender tries to
     * send an amount greater than its current balance
     * @throws \Ewallet\Memberships\InvalidTransfer If the sender tries to
     * transfer a negative amount
     * @throws \LogicException If no action is attached to this command
     */
    public function transfer(TransferFundsInput $input)
    {
        if (!$input->isValid()) {
            $this->invalidTransfer($input);
            return;
        }

        $this->command->transfer(TransferFundsInformation::from($input->values()));
    }

    private function invalidTransfer(TransferFundsInput $input)
    {
        $this->responder->respondToInvalidTransferInput(
            $input->errorMessages(),
            $input->values()
        );
    }

    public function transferCompleted(TransferFundsSummary $summary)
    {
        $this->responder->respondToTransferCompleted($summary);
    }
}
