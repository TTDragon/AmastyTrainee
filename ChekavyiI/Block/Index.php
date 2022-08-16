<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Block;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Index extends Template
{
    private FormKey $formKey;

    public function __construct(
        FormKey $formKey,
        Context $context,
        array $data = []
    ) {
        $this->formKey = $formKey;

        parent::__construct($context, $data);
    }

    public function sayHelloTo(string $name): string
    {
        return (string) __('Hello, %1', $name);
    }

    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }
}
