<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Templating;

interface TemplateEngine
{
    /** @param mixed[] $values */
    public function render(string $template, array $values): string;
}
