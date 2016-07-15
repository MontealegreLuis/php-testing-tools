<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Middleware;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

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
    public function __invoke(Request $request, Response $response)
    {
        $this->logRequest($request);

        /** @var Response $response */
        $response = $next($request, $response);

        if ($response->isNotFound()) {
            $this->logNotFound($request);
            return $response;
        }

        if ($response->isRedirect()) {
            $this->logRedirect($response);
            return $response;
        }

        $routeInfo = $request->getAttribute('routeInfo');
        var_dump($routeInfo);
        //$this->logRouteMatched();

        return $response;
    }

    private function logNotFound(Request $request)
    {
        $this->logger->info('No route matched', [
            'path' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
        ]);
    }

    private function logRedirect(Response $response)
    {
        $this->logger->info('Redirect', [
            'redirect' => $response->getHeader('Location')
        ]);
    }

    private function logRouteMatched()
    {
        $this->logger->info('Matched route ', [
            'route' => $this->app->router->getCurrentRoute()->getName(),
            'params' => $this->app->router->getCurrentRoute()->getParams(),
            'request' => $this->app->request->params(),
        ]);
    }

    private function logRequest(Request $request)
    {
        $this->logger->info('Current request', [
            'path' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
        ]);
    }
}
