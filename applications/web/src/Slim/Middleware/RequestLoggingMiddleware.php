<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim\Middleware;

use Psr\Log\LoggerInterface;
use Slim\Middleware;

class RequestLoggingMiddleware extends Middleware
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
    public function call()
    {
        $this->logRequest();

        $this->next->call();

        if ($this->app->response->isNotFound()) {
            return $this->logNotFound();
        }

        if ($this->app->response->isRedirect()) {
            return $this->logRedirect();
        }

        $this->logRouteMatched();
    }

    private function logNotFound()
    {
        $this->logger->info('No route matched', [
            'path' => $this->app->request->getPathInfo(),
            'method' => $this->app->request->getMethod(),
        ]);
    }

    private function logRedirect()
    {
        $this->logger->info('Redirect', [
            'redirect' => $this->app->response->headers->get('Location')
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

    private function logRequest()
    {
        $this->logger->info('Current request', [
            'path' => $this->app->request->getPathInfo(),
            'method' => $this->app->request->getMethod(),
        ]);
    }
}
