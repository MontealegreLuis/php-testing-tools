<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\DataBuilders;

class A
{
    /**
     * @return MembersBuilder
     */
    public static function member(): MembersBuilder
    {
        return new MembersBuilder();
    }

    /**
     * @return TransferWasMadeBuilder
     */
    public static function transferWasMadeEvent(): TransferWasMadeBuilder
    {
        return new TransferWasMadeBuilder();
    }
}
