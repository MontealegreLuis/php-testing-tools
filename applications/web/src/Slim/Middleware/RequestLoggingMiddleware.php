<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Route;

class RequestLoggingMiddleware
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log the current request information and its matched route, if any
     */
    public function __invoke(Request $request, ResponseInterface $response, $next)
    {
        $this->logRequest($request);

        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if (404 == $response->getStatusCode()) {
            $this->logNotFound($request);
            return $response;
        }

        if (in_array($response->getStatusCode(), [301, 302, 303, 307])) {
            $this->logRedirect($response);
            return $response;
        }

        $route = $request->getAttribute('route');
        $this->logRouteMatched($route, $request);

        return $response;
    }

    /**
     * @param Request $request
     */
    private function logNotFound(Request $request)
    {
        $this->logger->info('No route matched', [
            'path' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
        ]);
    }

    /**
     * @param ResponseInterface $response
     */
    private function logRedirect(ResponseInterface $response)
    {
        $this->logger->info('Redirect', [
            'redirect' => $response->getHeader('Location')[0],
        ]);
    }

    /**
     * @param Route $route
     * @param Request $request
     */
    private function logRouteMatched(Route $route, Request $request)
    {
        $this->logger->info('Matched route ', [
            'route' => $route->getName(),
            'params' => $route->getArguments(),
            'request' => $request->getParams(),
        ]);
    }

    /**
     * @param Request $request
     */
    private function logRequest(Request $request)
    {
        $this->logger->info('Current request', [
            'path' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
        ]);
    }
}
