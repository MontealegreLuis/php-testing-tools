<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2\Types;

use Ewallet\Accounts\Identifier;
use EWallet\Accounts\MemberId;

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the MemberId value object when querying.
 */
class MemberIdType extends UuidType
{
    /**
     * @param  string|null $value
     * @return Identifier
     */
    public function identifier($value)
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
