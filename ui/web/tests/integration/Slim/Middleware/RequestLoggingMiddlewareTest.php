<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Slim\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;
use UI\Slim\Middleware\RequestLoggingMiddleware;

class RequestLoggingMiddlewareTest extends TestCase
{
    /** @test */
    function it_logs_the_request_information()
    {
        $response = new Response(200);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_URI' => '/transfer-form',
            'REQUEST_METHOD' => 'GET',
        ]));
        $request = $request->withAttribute('route', new Route(
            ['GET'],
            '/transfer-form',
            $this->controller
        ));

        $this->middleware->__invoke($request, $response, $this->controller);

        $this
            ->logger
            ->info('Current request', [
                'path' => '/transfer-form',
                'method' => 'GET',
            ])
            ->shouldHaveBeenCalled()
        ;
    }

    /** @test */
    function it_logs_the_route_information()
    {
        $response = new Response(200);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_URI' => '/transfer-form',
            'REQUEST_METHOD' => 'GET',
        ]));
        $route = new Route(
            ['GET'],
            '/transfer-form',
            $this->controller
        );
        $route->setName('transfer_form');
        $request = $request->withAttribute('route', $route);

        $this->middleware->__invoke($request, $response, $this->controller);

        $this
            ->logger
            ->info('Matched route ', [
                'route' => 'transfer_form',
                'params' => [],
                'request' => [],
            ])
            ->shouldHaveBeenCalled()
        ;
    }

    /** @test */
    function it_logs_route_not_found()
    {
        $response = new Response(404);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/foo',
        ]));

        $this->middleware->__invoke($request, $response, $this->controller);

        $this
            ->logger
            ->info('No route matched', [
                'path' => '/foo',
                'method' => 'GET',
            ])
            ->shouldHaveBeenCalled()
        ;
    }

    /** @test */
    function it_logs_redirect()
    {
        $response = new Response(303);
        $response = $response->withAddedHeader('Location', '/transfer-form');
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
        ]));

        $this->middleware->__invoke($request, $response, $this->controller);

        $this
            ->logger
            ->info('Redirect', [
                'redirect' => '/transfer-form',
            ])
            ->shouldHaveBeenCalled()
        ;
    }

    /** @before */
    function configureMiddleware(): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->middleware = new RequestLoggingMiddleware($this->logger->reveal());
        $this->controller = function ($_, $response) {
            return $response;
        };
    }

    /** @var RequestLoggingMiddleware */
    private $middleware;

    /** @var callable */
    private $controller;

    /** @var LoggerInterface */
    private $logger;
}
