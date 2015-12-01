<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Actions;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsNotifier;
use Ewallet\Wallet\TransferFundsRequest;
use Ewallet\Wallet\TransferFundsResponse;
use EwalletModule\Responders\TransferFundsResponder;

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
     * @param Identifier $fromMemberId
     */
    public function enterTransferInformation(Identifier $fromMemberId)
    {
        $this->responder->respondToEnterTransferInformation($fromMemberId);
    }

    /**
     * @param FilteredRequest $request
     */
    public function transfer(FilteredRequest $request)
    {
        if (!$request->isValid()) {
            $this->validationFailedFor($request);
        } else {
            $this->useCase->transfer(TransferFundsRequest::from($request->values()));
        }
    }

    /**
     * @param FilteredRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function validationFailedFor(FilteredRequest $request)
    {
        $this->responder->respondToInvalidTransferInput(
            $request->errorMessages(),
            $request->values(),
            $request->value('fromMemberId')
        );
    }

    /**
     * @param TransferFundsResponse $response
     */
    public function transferCompleted(TransferFundsResponse $response)
    {
        $this->responder->respondToTransferCompleted($response);
    }

    /**
     * @return TransferFundsResponder
     */
    public function responder()
    {
        return $this->responder;
    }
}
