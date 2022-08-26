<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Block;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Index extends Template
{
    public const FORM_ACTION = 'chekavyii/index/addToCart';
    public const SEARCH_URL = 'chekavyii/index/searchProduct';

    /**
     * @var FormKey
     */
    private FormKey $formKey;

    public function __construct(
        FormKey $formKey,
        Context $context,
        array $data = []
    ) {
        $this->formKey = $formKey;

        parent::__construct($context, $data);
    }

    /**
     * @param string $name
     * @return string
     */
    public function sayHelloTo(string $name): string
    {
        return (string) __('Hello, %1', $name);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return string
     */
    public function getSearchUrl(): string
    {
        return $this->getUrl(self::SEARCH_URL);
    }

    /**
     * @return string
     */
    public function getFormAction(): string
    {
        return self::FORM_ACTION;
    }
}
