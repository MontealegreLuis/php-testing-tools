<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Forms\Elements;

use EasyForms\Elements\Element;
use EasyForms\Elements\Text;
use EasyForms\View\ElementView;
use EwalletModule\Forms\Elements\Views\MoneyView;

class MoneyElement extends Element
{
    /** @var Text */
    protected $amount;

    /** @var string */
    protected $currency;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->amount = new Text("{$name}[amount]");
        $this->currency = 'MXN';
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->amount->setValue($value);
        $this->value = [
            'amount' => $this->amount->value(),
            'currency' => $this->currency,
        ];
    }

    /**
     * @param ElementView $view
     * @return MoneyView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new MoneyView();

        /** @var MoneyView $view */
        $view = parent::buildView($view);

        $view->amount = $this->amount->buildView();
        $view->currency = $this->currency;
        $view->block = 'money';

        return $view;
    }
}
