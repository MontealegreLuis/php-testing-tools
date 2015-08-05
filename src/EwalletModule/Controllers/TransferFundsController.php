<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsRequest;

class TransferFundsController
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
    }

    /**
     * @param Identifier $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showForm(Identifier $fromMemberId)
    {
        return $this->responder->transferFundsFormResponse($fromMemberId);
    }

    /**
     * @param FilteredRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function transfer(FilteredRequest $request)
    {
        if (!$request->isValid()) {
            return $this->responder->invalidTransferInputResponse(
                $request->errorMessages(), $request->value('fromMemberId')
            );
        }

        $result = $this->useCase->transfer(TransferFundsRequest::from($request->values()));

        return $this->responder->successfulTransferResponse($result);
    }
}
