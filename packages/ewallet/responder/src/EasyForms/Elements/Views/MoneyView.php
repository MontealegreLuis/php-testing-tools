<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms\Elements\Views;

use EasyForms\View\ElementView;

class MoneyView extends ElementView
{
    /** @var ElementView */
    public $amount;

    /** @var string */
    public $currency;
}
