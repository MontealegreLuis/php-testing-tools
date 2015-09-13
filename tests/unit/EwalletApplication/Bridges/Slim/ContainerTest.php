<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use ComPHPPuebla\Slim\Resolver;
use EwalletApplication\Bridges\Slim\Middleware\RequestLoggingMiddleware;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;
use Slim\Slim;

class ContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_application_services()
    {
        $app = new Slim();
        $services = new Services(
            new Resolver(),
            require __DIR__ . '/../../../../../app/config.php'
        );

        $services->configure($app);

        $this->assertInstanceOf(
            LoggerInterface::class,
            $app->container->get('slim.logger')
        );
        $this->assertInstanceOf(
            RequestLoggingMiddleware::class,
            $app->container->get('slim.middleware.request_logging')
        );
    }
}
