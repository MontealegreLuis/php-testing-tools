<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms\Elements;

use EasyForms\{Elements\Element, Elements\Text, View\ElementView};
use Ewallet\EasyForms\Elements\Views\MoneyView;

class MoneyElement extends Element
{
    /** @var Text */
    protected $amount;

    /** @var string */
    protected $currency;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->amount = new Text("{$name}[amount]");
        $this->currency = 'MXN';
    }

    /**
     * @param mixed $value
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
