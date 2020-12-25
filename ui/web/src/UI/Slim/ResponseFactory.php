<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Teapot\StatusCode\All as Status;

final class ResponseFactory
{
    private ResponseFactoryInterface $factory;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function html(string $body = ''): ResponseInterface
    {
        $response = $this->factory->createResponse(Status::OK);
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'text/html');
    }

    public function redirect(UriInterface $uri): ResponseInterface
    {
        return $this->factory->createResponse(Status::SEE_OTHER)->withHeader('Location', (string) $uri);
    }
}
