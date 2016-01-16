<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Bridges\Doctrine2\Types;

use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use EWallet\Accounts\Identifier;
use InvalidArgumentException;

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the Identifier value object when querying.
 */
class UuidType extends Type
{
    /** @type string */
    const NAME = 'uuid';

    /**
     * @param  array $fieldDeclaration
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(
        array $fieldDeclaration, AbstractPlatform $platform
    ) {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

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
            $uuid = Identifier::with($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $uuid;
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

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return boolean
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
