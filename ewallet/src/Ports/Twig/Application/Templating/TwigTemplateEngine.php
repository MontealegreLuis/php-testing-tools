<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Twig\Application\Templating;

use Application\Templating\TemplateEngine;
use Twig_Environment as Twig;

class TwigTemplateEngine implements TemplateEngine
{
    /** @var Twig */
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $template, array $values): string
    {
        return $this->twig->render("{$template}.twig", $values);
    }
}
