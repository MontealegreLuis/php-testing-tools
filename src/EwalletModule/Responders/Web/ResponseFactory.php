<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Responders\Web;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactory
{
    /**
     * Builds an HTTP response with a 200 (OK) status code
     *
     * @param string $html
     * @return ResponseInterface
     */
    public function buildResponse($html);
}
