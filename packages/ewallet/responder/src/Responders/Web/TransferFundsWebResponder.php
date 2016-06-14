<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders\Web;

use Ewallet\Responders\TransferFundsResponder;
use Psr\Http\Message\ResponseInterface;

interface TransferFundsWebResponder extends TransferFundsResponder
{
    /**
     * @return ResponseInterface
     */
    public function response(): ResponseInterface;
}
