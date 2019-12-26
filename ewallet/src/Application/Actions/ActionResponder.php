<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Actions;

/**
 * All application responders provide feedback when user input does not pass validation
 */
interface ActionResponder
{
    public function respondToInvalidInput(InputValidator $input): void;
}
