<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFunds;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use Money\Money;
use Twig_Environment as Twig;
use Zend\Diactoros\Response;

class TransferFundsController
{
    /** @var Twig */
    private $view;

    /** @var TransferFundsForm */
    private $form;

    /** @var MembersConfiguration */
    private $configuration;

    /** @var TransferFunds */
    private $useCase;

    /**
     * @param Twig $view
     * @param TransferFundsForm $form
     * @param MembersConfiguration $configuration
     * @param TransferFunds $transferFunds
     */
    public function __construct(
        Twig $view,
        TransferFundsForm $form,
        MembersConfiguration $configuration,
        TransferFunds $transferFunds = null
    ) {
        $this->view = $view;
        $this->form = $form;
        $this->configuration = $configuration;
        $this->useCase = $transferFunds;
    }

    /**
     * @param Identifier $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showForm(Identifier $fromMemberId)
    {
        $this->form->configure($this->configuration, $fromMemberId);

        $response = new Response();
        $response
            ->getBody()
            ->write($this->view->render('member/transfer-funds.html.twig', [
                'form' => $this->form->buildView(),
            ]))
        ;

        return $response;
    }

    /**
     * @param Identifier $fromMemberId
     * @param Identifier $toMemberId
     * @param Money $amount
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function transfer(
        Identifier $fromMemberId, Identifier $toMemberId, Money $amount
    ) {
        $result = $this->useCase->transfer($fromMemberId, $toMemberId, $amount);

        $this->form->configure($this->configuration, $fromMemberId);

        $response = new Response();
        $response
            ->getBody()
            ->write($this->view->render('member/transfer-funds.html.twig', [
                'form' => $this->form->buildView(),
                'fromMember' => $result->fromMember(),
                'toMember' => $result->toMember(),
            ]))
        ;

        return $response;
    }
}
