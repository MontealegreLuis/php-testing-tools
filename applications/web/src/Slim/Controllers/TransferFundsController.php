<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Controllers;

use Ewallet\Accounts\MemberId;
use Ewallet\Actions\TransferFundsRequest;
use Ewallet\Actions\TransferFundsAction;
use Slim\Http\Request;

class TransferFundsController
{
    /** @var TransferFundsAction */
    private $action;

    /** @var TransferFundsRequest */
    private $request;

    /**
     * @param TransferFundsAction $action
     * @param TransferFundsRequest $request Only required when transfer is performed
     */
    public function __construct(
        TransferFundsAction $action,
        TransferFundsRequest $request = null
    ) {
        $this->action = $action;
        $this->request = $request;
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
        $this->request->populate($request->post());
        $this->action->transfer($this->request);

        $this->renderResponseBody();
    }

    /**
     * As Slim uses output buffering, we only need to `echo` the response built
     * by the responder.
     */
    private function renderResponseBody()
    {
        /** @var \EwalletModule\Responders\Web\TransferFundsWebResponder $responder */
        $responder = $this->action->responder();

        echo $responder->response()->getBody();
    }
}
