<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Block\Email;

use Magento\Framework\View\Element\Template;
use Amasty\ChekavyiI\Model\Blacklist;

class Email extends Template
{
    public function getEmail(): Blacklist
    {
       return  Blacklist;
    }
}