<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use UI\Slim\ResponseFactory;

final class RedirectToTransferFormController implements RequestHandlerInterface
{
    private ResponseFactory $response;

    private RouteParserInterface $router;

    public function __construct(ResponseFactory $response, RouteParserInterface $router)
    {
        $this->response = $response;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response->redirect($request->getUri()->withPath($this->router->urlFor('transfer_form')));
    }
}
