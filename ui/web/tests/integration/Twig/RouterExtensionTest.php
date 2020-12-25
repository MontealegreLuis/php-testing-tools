<?php
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Twig;

use Adapters\Twig\Application\Templating\RouterExtension;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Slim\Interfaces\RouteParserInterface;

final class RouterExtensionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_generates_url_for_named_route()
    {
        $this->router->urlFor('foo_action', [])->willReturn('/foo');

        $this->assertEquals('/foo', $this->extension->urlFor('foo_action'));
    }

    /** @test */
    function it_generates_url_for_named_route_with_base_path()
    {
        $this->router->urlFor('foo_action', [])->willReturn("{$this->basePath}/foo");

        $this->assertEquals(
            '/index_dev.php/foo',
            $this->extension->urlFor('foo_action')
        );
    }

    /** @test */
    function it_generates_url_for_named_route_with_arguments()
    {
        $this->router->urlFor('foo_action', ['name' => 'luis'])->willReturn('/foo/luis');

        $this->assertEquals(
            '/foo/luis',
            $this->extension->urlFor('foo_action', ['name' => 'luis'])
        );
    }

    /** @test */
    function it_generates_url_for_named_route_with_arguments_and_base_path()
    {
        $this->router->urlFor('foo_action', ['name' => 'luis'])->willReturn("{$this->basePath}/foo/luis");

        $this->assertEquals(
            '/index_dev.php/foo/luis',
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
    function let()
    {
        $this->router = $this->prophesize(RouteParserInterface::class);
        $this->extension = new RouterExtension($this->router->reveal());
    }

    private RouterExtension $extension;

    /** @var RouteParserInterface|ObjectProphecy */
    private $router;

    private string $basePath = '/index_dev.php';
}
