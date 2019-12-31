<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Twig;

use Adapters\Twig\Application\Templating\RouterExtension;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Router;

class RouterExtensionTest extends TestCase
{
    /** @test */
    function it_generates_url_for_named_route()
    {
        $this->router->map(['GET'], '/foo', $this->controller)->setName('foo_action');

        $this->assertEquals('/foo', $this->extension->urlFor('foo_action'));
    }

    /** @test */
    function it_generates_url_for_named_route_with_base_path()
    {
        $this
            ->router
            ->map(['GET'], "$this->basePath/foo", $this->controller)
            ->setName('foo_action')
        ;

        $this->assertEquals(
            "$this->basePath/foo",
            $this->extension->urlFor('foo_action')
        );
    }

    /** @test */
    function it_generates_url_for_named_route_with_arguments()
    {
        $this
            ->router
            ->map(['GET'], '/foo/{name}', $this->controller)
            ->setName('foo_action')
        ;

        $this->assertEquals(
            '/foo/luis',
            $this->extension->urlFor('foo_action', ['name' => 'luis'])
        );
    }

    /** @test */
    function it_generates_url_for_named_route_with_arguments_and_base_path()
    {
        $this
            ->router
            ->map(['GET'], "$this->basePath/foo/{name}", $this->controller)
            ->setName('foo_action')
        ;

        $this->assertEquals(
            "$this->basePath/foo/luis",
            $this->extension->urlFor('foo_action', ['name' => 'luis'])
        );
    }

    /** @test */
    function it_generates_url_for_asset()
    {
        $this->assertEquals(
            '/assets/styles/app.css',
            $this->extension->asset('/assets/styles/app.css')
        );
    }

    /** @before */
    function configureExtension(): void
    {
        $this->router = new Router();
        $this->controller = function () {
        };
        $this->extension = new RouterExtension(
            $this->router,
            Request::createFromEnvironment(Environment::mock())
        );
    }

    /** @var RouterExtension */
    private $extension;

    /** @var callable */
    private $controller;

    /** @var Router */
    private $router;

    /** @var string */
    private $basePath = '/index_dev.php';
}
