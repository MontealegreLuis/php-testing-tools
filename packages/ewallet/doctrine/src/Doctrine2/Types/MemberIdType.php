<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2\Types;

use Ewallet\Accounts\{Identifier, MemberId};

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the MemberId value object when querying.
 */
class MemberIdType extends UuidType
{
    /**
     * @param  string $value
     * @return Identifier
     */
    public function identifier(string $value): Identifier
    {
        return MemberId::with($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MemberId';
    }
}
