<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\Middleware;

use Monolog\Logger;
use Slim\Middleware;

class RequestLoggingMiddleware extends Middleware
{
    /**
     * Log the current request information and its matched route, if any
     */
    public function call()
    {
        /** @var Logger $logger */
        $logger = $this->app->container->get('logger.slim');

        $this->logRequest($logger);

        $this->next->call();

        if ($this->app->response->isNotFound()) {
            return $this->logNotFound($logger);
        }

        if ($this->app->response->isRedirect()) {
            return $this->logRedirect($logger);
        }

        $this->logRouteMatched($logger);
    }

    /**
     * @param Logger $logger
     */
    private function logNotFound(Logger $logger)
    {
        $logger->info('No route matched', [
            'path' => $this->app->request->getPathInfo(),
            'method' => $this->app->request->getMethod(),
        ]);
    }

    /**
     * @param Logger $logger
     */
    private function logRedirect(Logger $logger)
    {
        $logger->info('Redirect', [
            'redirect' => $this->app->response->headers->get('Location')
        ]);
    }

    /**
     * @param Logger $logger
     */
    private function logRouteMatched(Logger $logger)
    {
        $logger->info('Matched route ', [
            'route' => $this->app->router->getCurrentRoute()->getName(),
            'params' => $this->app->router->getCurrentRoute()->getParams(),
            'request' => $this->app->request->params(),
        ]);
    }

    /**
     * @param Logger $logger
     */
    private function logRequest(Logger $logger)
    {
        $logger->info('Current request', [
            'path' => $this->app->request->getPathInfo(),
            'method' => $this->app->request->getMethod(),
        ]);
    }
}
