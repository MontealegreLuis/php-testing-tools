<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

use Ewallet\Accounts\MemberId;
use Ewallet\Responders\TransferFundsResponder;
use Ewallet\Wallet\{TransferFunds, TransferFundsNotifier, TransferFundsInformation, TransferFundsSummary};

/**
 * This is a two steps action. First, the member making the transfer enters the
 * information needed to perform it. Once information is provided, the transfer
 * is attempted. If the information is invalid, the member receives the
 * appropriate feedback in order to fix the errors. Otherwise the transfer
 * completes.
 */
class TransferFundsAction implements TransferFundsNotifier
{
    /** @var TransferFunds */
    private $useCase;

    /** @var TransferFundsResponder */
    private $responder;

    /**
     * @param TransferFundsResponder $responder
     * @param TransferFunds $transferFunds
     */
    public function __construct(
        TransferFundsResponder $responder,
        TransferFunds $transferFunds = null
    ) {
        $this->responder = $responder;
        $this->useCase = $transferFunds;
        $transferFunds && $this->useCase->attach($this);
    }

    /**
     * @param MemberId $fromMemberId
     */
    public function enterTransferInformation(MemberId $fromMemberId)
    {
        $this->responder->respondToEnterTransferInformation($fromMemberId);
    }

    /**
     * @param TransferFundsInput $input
     */
    public function transfer(TransferFundsInput $input)
    {
        if (!$input->isValid()) {
            $this->validationFailedFor($input);
        } else {
            $this->useCase->transfer(TransferFundsInformation::from($input->values()));
        }
    }

    /**
     * @param TransferFundsInput $input
     */
    private function validationFailedFor(TransferFundsInput $input)
    {
        $this->responder->respondToInvalidTransferInput(
            $input->errorMessages(),
            $input->values()
        );
    }

    /**
     * @param TransferFundsSummary $summary
     */
    public function transferCompleted(TransferFundsSummary $summary)
    {
        $this->responder->respondToTransferCompleted($summary);
    }

    /**
     * @return TransferFundsResponder
     */
    public function responder(): TransferFundsResponder
    {
        return $this->responder;
    }
}
