<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Twig\Application\Templating;

use Slim\Interfaces\RouteParserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction as SimpleFunction;

/**
 * Registers the function `url_for` in order to be able to use Slim named routes
 * in Twig templates
 */
final class RouterExtension extends AbstractExtension
{
    private RouteParserInterface $router;

    public function __construct(RouteParserInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return SimpleFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new SimpleFunction(
                'url_for',
                fn (string $routeName, array $arguments = []): string => $this->urlFor($routeName, $arguments)
            ),
            new SimpleFunction('asset', fn (string $path): string => $this->asset($path)),
        ];
    }

    /** @param mixed[] $arguments */
    public function urlFor(string $routeName, array $arguments = []): string
    {
        return $this->router->urlFor($routeName, $arguments);
    }

    public function asset(string $path): string
    {
        return (string) preg_replace(
            '#/+#',
            '/',
            sprintf('%s%s', '/', $path)
        );
    }
}
