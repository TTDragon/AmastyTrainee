<?php

namespace Amasty\ChekavyiI\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template
{
    public function sayHelloTo(string $name): string
    {
        return (string) __('Hello, %1', $name);
    }
}
