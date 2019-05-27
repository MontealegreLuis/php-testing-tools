<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Ewallet\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;
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
        if (empty($value)) {
            return null;
        }

        try {
            return $this->identifier($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, Type::GUID);
        }
    }

    /**
     * @throws ConversionException
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Identifier) {
            return (string) $value;
        } elseif (is_string($value)) {
            return $value;
        }

        throw ConversionException::conversionFailed($value, Type::GUID);
    }

    abstract public function identifier(string $value): Identifier;
}
