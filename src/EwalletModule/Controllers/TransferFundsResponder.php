<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFundsResult;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use Twig_Environment as Twig;
use Zend\Diactoros\Response;

class TransferFundsResponder
{
    /** @var Twig */
    private $view;

    /** @var TransferFundsForm */
    private $form;

    /** @var MembersConfiguration */
    private $configuration;

    /**
     * @param Twig $view
     * @param TransferFundsForm $form
     * @param MembersConfiguration $configuration
     */
    public function __construct(
        Twig $view,
        TransferFundsForm $form,
        MembersConfiguration $configuration
    ) {
        $this->view = $view;
        $this->form = $form;
        $this->configuration = $configuration;
    }

    /**
     * @param TransferFundsResult $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function successfulTransferResponse(TransferFundsResult $result)
    {
        $this->form->configure($this->configuration, $result->fromMember()->id());

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

    /**
     * @param array $messages
     * @param string $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function invalidTransferInputResponse(array $messages, $fromMemberId)
    {
        $this->form->setErrorMessages($messages);

        return $this->transferFundsFormResponse(Identifier::fromString($fromMemberId));
    }

    /**
     * @param Identifier $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function transferFundsFormResponse(Identifier $fromMemberId)
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
}
