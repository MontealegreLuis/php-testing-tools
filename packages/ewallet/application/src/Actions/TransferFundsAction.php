<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

use Ewallet\Accounts\MemberId;
use Ewallet\Responders\TransferFundsResponder;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsNotifier;
use Ewallet\Wallet\TransferFundsInformation;
use Ewallet\Wallet\TransferFundsResult;

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
     * @param TransferFundsRequest $request
     */
    public function transfer(TransferFundsRequest $request)
    {
        if (!$request->isValid()) {
            $this->validationFailedFor($request);
        } else {
            $this->useCase->transfer(TransferFundsInformation::from($request->values()));
        }
    }

    /**
     * @param TransferFundsRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function validationFailedFor(TransferFundsRequest $request)
    {
        $this->responder->respondToInvalidTransferInput(
            $request->errorMessages(),
            $request->values()
        );
    }

    /**
     * @param TransferFundsResult $result
     */
    public function transferCompleted(TransferFundsResult $result)
    {
        $this->responder->respondToTransferCompleted($result);
    }

    /**
     * @return TransferFundsResponder
     */
    public function responder()
    {
        return $this->responder;
    }
}
