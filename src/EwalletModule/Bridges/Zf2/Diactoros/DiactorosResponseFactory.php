<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\Diactoros;

use EwalletModule\Controllers\ResponseFactory;
use Zend\Diactoros\Response;

class DiactorosResponseFactory implements ResponseFactory
{
    /**
     * Builds an HTTP response with a 200 (OK) status code
     *
     * @param string $html
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function buildResponse($html)
    {
        $response = new Response();
        $response->getBody()->write($html);

        return $response;
    }
}
