<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders;

use Ewallet\Accounts\MemberId;
use Ewallet\EasyForms\{MembersConfiguration, TransferFundsForm};
use Ewallet\Wallet\TransferFundsResult;
use Ewallet\Responders\Web\{ResponseFactory, TransferFundsWebResponder};
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
     * @param TransferFundsResult $result
     */
    public function respondToTransferCompleted(TransferFundsResult $result)
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
     */
    public function respondToInvalidTransferInput(
        array $messages,
        array $values
    ) {
        $this->form->submit($values);
        $this->form->setErrorMessages($messages);

        $this->respondToEnterTransferInformation(MemberId::with($values['fromMemberId']));
    }

    /**
     * @param MemberId $fromMemberId
     */
    public function respondToEnterTransferInformation(MemberId $fromMemberId)
    {
        $this->form->configure($this->configuration, $fromMemberId);

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
