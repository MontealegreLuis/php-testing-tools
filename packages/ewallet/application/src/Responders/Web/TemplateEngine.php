<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders\Web;

interface TemplateEngine
{
    /**
     * @param string $template
     * @param array $values
     * @return string
     */
    public function render($template, array $values);
}
