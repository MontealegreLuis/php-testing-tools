<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Twig;

use Slim\Http\Request;
use Slim\Router;
use Twig_SimpleFunction as SimpleFunction;
use Twig_Extension as Extension;

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

    /**
     * @param Router $router
     */
    public function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('url_for', [$this, 'urlFor']),
        ];
    }

    /**
     * @param string $routeName
     * @param array $arguments
     * @return string
     */
    public function urlFor($routeName, array $arguments = [])
    {
        return sprintf(
            '%s%s',
            $this->request->getRootUri(),
            $this->router->urlFor($routeName, $arguments)
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'slim_router';
    }
}
