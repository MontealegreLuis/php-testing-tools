<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Templating;

interface TemplateEngine
{
    /**
     * @param string $template
     * @param array $values
     * @return string
     */
    public function render(string $template, array $values): string;
}
