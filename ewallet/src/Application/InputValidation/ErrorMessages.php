<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\InputValidation;

final class ErrorMessages
{
    /** @var string Matches property paths with array format: `receipts[1]` */
    private const COLLECTION_PATH = '/(\w+)\[(\d+)]/';

    /** @var mixed[] */
    private array $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    /** @return mixed[] */
    public function messages(): array
    {
        return $this->errors;
    }

    public function isEmpty(): bool
    {
        return count($this->errors) === 0;
    }

    public function add(string $propertyPath, string $errorMessage): void
    {
        $this->assignMessageByPath($this->errors, $propertyPath, $errorMessage);
    }

    /** @param mixed[] $errors */
    private function assignMessageByPath(array &$errors, string $propertyPath, string $errorMessage): void
    {
        $keys = explode('.', $propertyPath);

        foreach ($keys as $key) {
            if (preg_match(self::COLLECTION_PATH, $key, $matches) === 1 && count($matches) === 3) {
                $errors = &$errors[$matches[1]];
                $errors = &$errors[(int) $matches[2]];
            } else {
                $errors = &$errors[$key];
            }
        }

        $errors = $errorMessage;
    }
}
