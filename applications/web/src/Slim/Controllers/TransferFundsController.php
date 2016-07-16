<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Controllers;

use Ewallet\Accounts\MemberId;
use Ewallet\Wallet\{TransferFundsInput, TransferFundsAction};
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
     */
    public function enterTransferInformation()
    {
        $this->action->enterTransferInformation(MemberId::with('ABC'));

        $this->renderResponseBody();
    }

    /**
     * Perform the transfer
     *
     * @param Request $request
     */
    public function transfer(Request $request)
    {
        $this->input->populate($request->getParsedBody());
        $this->action->transfer($this->input);

        $this->renderResponseBody();
    }

    /**
     * As Slim uses output buffering, we only need to `echo` the response built
     * by the responder.
     */
    private function renderResponseBody()
    {
        /** @var \Ewallet\Wallet\Web\TransferFundsWebResponder $responder */
        $responder = $this->action->responder();

        echo $responder->response()->getBody();
    }
}
