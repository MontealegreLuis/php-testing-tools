<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\Web;

use Ewallet\ManageWallet\TransferFundsResponder;
use Psr\Http\Message\ResponseInterface;

/**
 * A web responder generates an HTTP response
 */
interface TransferFundsWebResponder extends TransferFundsResponder
{
    public function response(): ResponseInterface;
}
