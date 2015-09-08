<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\EasyForms;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFundsResponse;
use EwalletModule\Responders\Web\ResponseFactory;
use EwalletModule\Responders\Web\TemplateEngine;
use EwalletModule\Responders\TransferFundsWebResponder;

class TransferFundsFormResponder implements TransferFundsWebResponder
{
    /** @var ResponseFactory */
    private $factory;

    /** @var \Psr\Http\Message\ResponseInterface */
    private $response;

    /** @var TemplateEngine */
    private $template;

    /** @var TransferFundsForm */
    private $form;

    /** @var MembersConfiguration */
    private $configuration;

    /**
     * @param TemplateEngine $template
     * @param ResponseFactory $factory
     * @param TransferFundsForm $form
     * @param MembersConfiguration $configuration
     */
    public function __construct(
        TemplateEngine $template,
        ResponseFactory $factory,
        TransferFundsForm $form,
        MembersConfiguration $configuration
    ) {
        $this->template = $template;
        $this->factory = $factory;
        $this->form = $form;
        $this->configuration = $configuration;
    }

    /**
     * @param TransferFundsResponse $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function respondTransferCompleted(TransferFundsResponse $result)
    {
        $this->form->configure($this->configuration, $result->fromMember()->id());

        $html = $this->template->render('member/transfer-funds.html', [
            'form' => $this->form->buildView(),
            'fromMember' => $result->fromMember(),
            'toMember' => $result->toMember(),
        ]);

        $this->response = $this->factory->buildResponse($html);
    }

    /**
     * @param array $messages
     * @param array $values
     * @param string $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function respondInvalidTransferInput(
        array $messages, array $values, $fromMemberId
    ) {
        $this->form->submit($values);
        $this->form->setErrorMessages($messages);

        $this->respondEnterTransferInformation(Identifier::fromString($fromMemberId));
    }

    /**
     * @param Identifier $fromMemberId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function respondEnterTransferInformation(Identifier $fromMemberId)
    {
        $this->form->configure($this->configuration, $fromMemberId);

        $html = $this->template->render('member/transfer-funds.html', [
            'form' => $this->form->buildView(),
        ]);

        $this->response = $this->factory->buildResponse($html);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response()
    {
        return $this->response;
    }
}
