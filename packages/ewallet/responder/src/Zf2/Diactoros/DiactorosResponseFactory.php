<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\Diactoros;

use Ewallet\ManageWallet\Web\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class DiactorosResponseFactory implements ResponseFactory
{
    /**
     * Builds an HTTP response with a 200 (OK) status code
     *
     * @param string $html
     * @return ResponseInterface
     */
    public function buildResponse(string $html): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($html);

        return $response;
    }
}
