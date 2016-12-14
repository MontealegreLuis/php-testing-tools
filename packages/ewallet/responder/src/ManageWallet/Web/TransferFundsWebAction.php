<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\Web;

use Ewallet\ManageWallet\{TransferFunds, TransferFundsAction};
use Psr\Http\Message\ResponseInterface;

class TransferFundsWebAction extends TransferFundsAction
{
    /** @var TransferFundsWebResponder */
    protected $responder;

    public function __construct(
        TransferFundsWebResponder $responder,
        TransferFunds $transferFunds = null
    ) {
        parent::__construct($responder, $transferFunds);
    }

    public function response(): ResponseInterface
    {
        return $this->responder->response();
    }
}
