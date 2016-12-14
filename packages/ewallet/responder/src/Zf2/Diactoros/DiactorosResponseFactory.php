<?php
/**
 * PHP version 7.1
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
     * @throws \RuntimeException If an invalid status code is given when the
     * response is created
     * @throws \InvalidArgumentException If there's an error while reading the
     * response
     */
    public function buildResponse(string $html): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($html);

        return $response;
    }
}
