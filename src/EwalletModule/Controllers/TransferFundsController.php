<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use EwalletModule\Forms\TransferFundsForm;
use Twig_Environment as Twig;
use Zend\Diactoros\Response;

class TransferFundsController
{
    /** @var Twig */
    private $view;

    /** @var TransferFundsForm */
    private $form;

    /**
     * @param Twig $view
     * @param TransferFundsForm $form
     */
    public function __construct(Twig $view, TransferFundsForm $form)
    {
        $this->view = $view;
        $this->form = $form;
    }

    /**
     * @param Identifier $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showForm(Identifier $fromMemberId)
    {
    }
}
