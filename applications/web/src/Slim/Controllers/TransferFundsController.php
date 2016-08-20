<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Controllers;

use Ewallet\Memberships\MemberId;
use Ewallet\ManageWallet\{TransferFundsInput, TransferFundsAction};
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class TransferFundsController
{
    /** @var TransferFundsAction */
    private $action;

    /** @var TransferFundsInput */
    private $input;

    /**
     * @param TransferFundsAction $action
     * @param TransferFundsInput $input Only required when transfer is performed
     */
    public function __construct(
        TransferFundsAction $action,
        TransferFundsInput $input = null
    ) {
        $this->action = $action;
        $this->input = $input;
    }

    /**
     * Show the form to transfer funds between members
     *
     * @return ResponseInterface
     */
    public function enterTransferInformation(): ResponseInterface
    {
        $this->action->enterTransferInformation(MemberId::with('ABC'));

        return $this->action->responder()->response();
    }

    /**
     * Perform the transfer
     *
     * @param Request $request
     * @return ResponseInterface
     */
    public function transfer(Request $request): ResponseInterface
    {
        $this->input->populate($request->getParsedBody());
        $this->action->transfer($this->input);

        return $this->action->responder()->response();
    }
}
