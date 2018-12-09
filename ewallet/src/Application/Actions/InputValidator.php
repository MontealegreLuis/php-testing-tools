<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Actions;

/**
 * All application actions must validate its input before being executed
 */
interface InputValidator
{
    /** Is the raw input valid? */
    public function isValid(): bool;

    /** @return string[] */
    public function errors(): array;

    /**
     * The original raw input.
     *
     * This is usually shown back to the user through the UI, to provide feedback when validation fails
     */
    public function values(): array;
}
