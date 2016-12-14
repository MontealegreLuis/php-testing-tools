<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\Web;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactory
{
    /**
     * Builds an HTTP response with a 200 (OK) status code
     */
    public function buildResponse(string $html): ResponseInterface;
}
