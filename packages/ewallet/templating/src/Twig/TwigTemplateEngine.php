<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Twig;

use Ewallet\Templating\TemplateEngine;
use Twig_Environment as Twig;

class TwigTemplateEngine implements TemplateEngine
{
    /** @var Twig */
    private $twig;

    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $template
     * @param array $values
     * @return string
     */
    public function render(string $template, array $values): string
    {
        return $this->twig->render("{$template}.twig", $values);
    }
}
