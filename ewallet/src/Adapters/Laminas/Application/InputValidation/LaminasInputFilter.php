<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Laminas\Application\InputValidation;

use Application\InputValidation\InputFilter;
use Laminas\Filter\StringTrim;
use Laminas\Filter\ToInt;

final class LaminasInputFilter implements InputFilter
{
    /** @var mixed[] */
    private array $values;

    private StringTrim $trim;

    private ToInt $integer;

    /** @param mixed[] $values */
    public function __construct(array $values)
    {
        $this->values = $values;
        $this->trim = new StringTrim();
        $this->integer = new ToInt();
    }

    public function trim(string $key): ?string
    {
        if (! isset($this->values[$key]) || ! is_scalar($this->values[$key])) {
            return null;
        }

        return $this->trim->filter((string) $this->values[$key]);
    }

    public function integer(string $key, int $default = null): ?int
    {
        if (! isset($this->values[$key]) || ! is_numeric($this->values[$key])) {
            return $default;
        }

        return $this->integer->filter($this->values[$key]);
    }
}
