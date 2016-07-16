<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Twig;

use PHPUnit_Framework_TestCase as TestCase;
use Slim\Http\{Environment, Request};
use Slim\Router;

class RouterExtensionTest extends TestCase
{
    /** @test */
    function it_generates_url_for_named_route()
    {
        $router = new Router();
        $router->map(['GET'], '/foo', function() {})->setName('foo_action');
        $request = Request::createFromEnvironment(Environment::mock());

        $extension = new RouterExtension($router, $request);

        $this->assertEquals('/foo', $extension->urlFor('foo_action'));
    }

    /** @test */
    function it_generates_url_for_named_route_with_base_path()
    {
        $router = new Router();
        $basePath = '/index_dev.php';
        $router
            ->map(['GET'], "$basePath/foo", function() {})
            ->setName('foo_action')
        ;
        $request = Request::createFromEnvironment(Environment::mock());

        $extension = new RouterExtension($router, $request);

        $this->assertEquals("$basePath/foo", $extension->urlFor('foo_action'));
    }

    /** @test */
    function it_generates_url_for_named_route_with_arguments()
    {
        $router = new Router();
        $router
            ->map(['GET'], '/foo/{name}', function() {})
            ->setName('foo_action')
        ;
        $request = Request::createFromEnvironment(Environment::mock());

        $extension = new RouterExtension($router, $request);

        $this->assertEquals(
            '/foo/luis',
            $extension->urlFor('foo_action', ['name' => 'luis'])
        );
    }

    /** @test */
    function it_generates_url_for_named_route_with_arguments_and_base_path()
    {
        $router = new Router();
        $basePath = '/index_dev.php';
        $router
            ->map(['GET'], "$basePath/foo/{name}", function() {})
            ->setName('foo_action')
        ;
        $request = Request::createFromEnvironment(Environment::mock());

        $extension = new RouterExtension($router, $request);

        $this->assertEquals(
            "$basePath/foo/luis",
            $extension->urlFor('foo_action', ['name' => 'luis'])
        );
    }

    /** @test */
    function it_generates_url_for_asset()
    {
        $router = new Router();
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_URI' => '/foo',
        ]));

        $extension = new RouterExtension($router, $request);

        $this->assertEquals(
            '/assets/styles/app.css',
            $extension->asset('/assets/styles/app.css')
        );
    }

    /** @test */
    function it_generates_url_for_asset_with_base_path()
    {
        $router = new Router();
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_URI' => '/index_dev.php/foo',
        ]));

        $extension = new RouterExtension($router, $request);

        $this->assertEquals(
            '/assets/styles/app.css',
            $extension->asset('/assets/styles/app.css')
        );
    }
}
