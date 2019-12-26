<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Templating;

interface TemplateEngine
{
    public function render(string $template, array $values): string;
}
