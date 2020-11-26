<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Webmozart\Assert\Assert;

abstract class Identifier
{
    private string $value;

    public function equals(Identifier $anotherId): bool
    {
        return $this->value === $anotherId->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __construct(string $value)
    {
        $this->setId(trim($value));
    }

    private function setId(string $value): void
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }
}
