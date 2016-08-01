<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2\Types;

use Doctrine\DBAL\Types\{ConversionException, GuidType, Type};
use Doctrine\DBAL\Platforms\AbstractPlatform;
use EWallet\Accounts\Identifier;
use InvalidArgumentException;

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the Identifier value object when querying.
 */
abstract class UuidType extends GuidType
{
    /**
     * @param  string|null $value
     * @param  AbstractPlatform $platform
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
     * @param  Identifier|null $value
     * @param  AbstractPlatform $platform
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

    /**
     * @param  string $value
     * @return Identifier
     */
    abstract public function identifier(string $value): Identifier;
}
