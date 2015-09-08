<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Twig;

use EwalletModule\Responders\Web\TemplateEngine;
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
    public function render($template, array $values)
    {
        return $this->twig->render("{$template}.twig", $values);
    }
}
