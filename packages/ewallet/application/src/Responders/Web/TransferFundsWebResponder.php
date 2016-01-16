<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders\Web;

use Ewallet\Responders\TransferFundsResponder;

interface TransferFundsWebResponder extends TransferFundsResponder
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response();
}
