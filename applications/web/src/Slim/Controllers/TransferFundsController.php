<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Controllers;

use Ewallet\Memberships\MemberId;
use Ewallet\ManageWallet\{TransferFundsInput, Web\TransferFundsWebAction};
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class TransferFundsController
{
    /** @var TransferFundsWebAction */
    private $action;

    /** @var TransferFundsInput */
    private $input;

    public function __construct(TransferFundsWebAction $action, TransferFundsInput $input = null)
    {
        $this->action = $action;
        $this->input = $input;
    }

    /**
     * Show the form to transfer funds between members
     */
    public function enterTransferInformation(): ResponseInterface
    {
        $this->action->enterTransferInformation(MemberId::withIdentity('ABC'));

        return $this->action->response();
    }

    /**
     * Perform the transfer
     *
     * @throws \Ewallet\Memberships\InsufficientFunds If the sender does not
     * have sufficient funds
     * @throws \Ewallet\Memberships\InvalidTransfer If the sender tries to
     * transfer a negative amount
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or the
     * recipient are unknown
     */
    public function transfer(Request $request): ResponseInterface
    {
        $this->input->populate($request->getParsedBody());
        $this->action->transfer($this->input);

        return $this->action->response();
    }
}
