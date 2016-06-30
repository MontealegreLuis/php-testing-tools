<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\MemberId;
use Ewallet\EasyForms\{MembersConfiguration, TransferFundsForm};
use Ewallet\Wallet\Web\{ResponseFactory, TransferFundsWebResponder};
use Ewallet\Templating\TemplateEngine;
use Psr\Http\Message\ResponseInterface;

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
     * @param TransferFundsSummary $summary
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary)
    {
        $this->form->configure($this->configuration, $summary->sender()->id());

        $html = $this->template->render('member/transfer-funds.html', [
            'form' => $this->form->buildView(),
            'sender' => $summary->sender(),
            'recipient' => $summary->recipient(),
        ]);

        $this->response = $this->factory->buildResponse($html);
    }

    /**
     * @param array $messages
     * @param array $values
     */
    public function respondToInvalidTransferInput(
        array $messages,
        array $values
    ) {
        $this->form->submit($values);
        $this->form->setErrorMessages($messages);

        $this->respondToEnterTransferInformation(MemberId::with($values['senderId']));
    }

    /**
     * @param MemberId $senderId
     */
    public function respondToEnterTransferInformation(MemberId $senderId)
    {
        $this->form->configure($this->configuration, $senderId);

        $html = $this->template->render('member/transfer-funds.html', [
            'form' => $this->form->buildView(),
        ]);

        $this->response = $this->factory->buildResponse($html);
    }

    /**
     * @return ResponseInterface
     */
    public function response(): ResponseInterface
    {
        return $this->response;
    }
}
