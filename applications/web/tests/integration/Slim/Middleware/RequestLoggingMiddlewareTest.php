<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Middleware;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;
use Slim\Http\{Environment, Request, Response};
use Slim\Route;

class RequestLoggingMiddlewareTest extends TestCase
{
    /** @test */
    function it_logs_the_request_information()
    {
        $logger = Mockery::spy(LoggerInterface::class);
        $response = new Response();
        $middleware = new RequestLoggingMiddleware($logger);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_URI' => '/transfer-form',
            'REQUEST_METHOD' => 'GET',
        ]));
        $request = $request->withAttribute('route', new Route(
            ['GET'],
            '/transfer-form',
            function($_, $response) {
                return $response;
            }
        ));

        $middleware($request, $response, function($_, $response) {
            return $response;
        });

        $logger
            ->shouldHaveReceived('info')
            ->with('Current request', [
                'path' => '/transfer-form',
                'method' => 'GET',
            ])
        ;
    }

    /** @test */
    function it_logs_the_route_information()
    {
        $logger = Mockery::spy(LoggerInterface::class);
        $response = new Response();
        $middleware = new RequestLoggingMiddleware($logger);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_URI' => '/transfer-form',
            'REQUEST_METHOD' => 'GET',
        ]));
        $route = new Route(
            ['GET'],
            '/transfer-form',
            function($_, $response) {
                return $response;
            }
        );
        $route->setName('transfer_form');
        $request = $request->withAttribute('route', $route);

        $middleware($request, $response, function($_, $response) {
            return $response;
        });

        $logger
            ->shouldHaveReceived('info')
            ->with('Matched route ', [
                'route' => 'transfer_form',
                'params' => [],
                'request' => [],
            ])
        ;
    }

    /** @test */
    function it_logs_route_not_found()
    {
        $logger = Mockery::spy(LoggerInterface::class);
        $response = new Response(404);
        $middleware = new RequestLoggingMiddleware($logger);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/foo',
        ]));

        $middleware($request, $response, function($_, $response) {
            return $response;
        });

        $logger
            ->shouldHaveReceived('info')
            ->with('No route matched', [
                'path' => '/foo',
                'method' => 'GET',
            ])
        ;
    }

    /** @test */
    function it_logs_redirect()
    {
        $logger = Mockery::spy(LoggerInterface::class);
        $response = new Response(303);
        $response = $response->withAddedHeader('Location', '/transfer-form');
        $middleware = new RequestLoggingMiddleware($logger);
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
        ]));

        $middleware($request, $response, function($_, $response) {
            return $response;
        });

        $logger
            ->shouldHaveReceived('info')
            ->with('Redirect', [
                'redirect' => '/transfer-form',
            ])
        ;
    }
}
