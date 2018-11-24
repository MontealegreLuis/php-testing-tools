<?php
/**
 * PHP version 7.1
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

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function render(string $template, array $values): string
    {
        return $this->twig->render("{$template}.twig", $values);
    }
}
