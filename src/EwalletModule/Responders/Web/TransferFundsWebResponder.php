<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Responders\Web;

use EwalletModule\Responders\TransferFundsResponder;

interface TransferFundsWebResponder extends TransferFundsResponder
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response();
}
