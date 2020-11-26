<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Ewallet\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Types;
use Ewallet\Memberships\Identifier;
use InvalidArgumentException;

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the Identifier value object when querying.
 */
abstract class UuidType extends GuidType
{
    /**
     * @throws ConversionException
     * @return Identifier|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value ===  null) {
            return null;
        }

        try {
            return $this->identifier($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, Types::GUID);
        }
    }

    /**
     * @throws ConversionException
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Identifier) {
            return (string) $value;
        }

        if (is_string($value)) {
            return $value;
        }

        throw ConversionException::conversionFailed((string) $value, Types::GUID);
    }

    abstract public function identifier(string $value): Identifier;
}
