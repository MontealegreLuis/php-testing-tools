<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Templating;

interface TemplateEngine
{
    public function render(string $template, array $values): string;
}
