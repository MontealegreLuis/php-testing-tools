<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Twig\Application\Templating;

use Slim\Http\Request;
use Slim\Router;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

/**
 * Registers the function `url_for` in order to be able to use Slim named routes
 * in Twig templates
 */
class RouterExtension extends Extension
{
    /** @var Router */
    private $router;

    /** @var Request */
    private $request;

    public function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    /**
     * @return SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('url_for', [$this, 'urlFor']),
            new SimpleFunction('asset', [$this, 'asset']),
        ];
    }

    public function urlFor(string $routeName, array $arguments = []): string
    {
        return $this->router->pathFor($routeName, $arguments);
    }

    public function asset(string $path): string
    {
        /** @var \Slim\Http\Uri $uri */
        $uri = $this->request->getUri();
        return (string) preg_replace(
            '#/+#',
            '/',
            sprintf('%s%s', \dirname($uri->getBasePath()), $path)
        );
    }
}
