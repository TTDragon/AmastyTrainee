<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Block\Email;

use Amasty\ChekavyiI\Model\Blacklist;
use Amasty\ChekavyiI\Model\BlacklistRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Email extends Template
{
    /**
     * @var BlacklistRepository
     */
    private BlacklistRepository $blacklistRepository;

    public function __construct(
        Context $context,
        BlacklistRepository $blacklistRepository,
        array $data = []
    ) {
        $this->blacklistRepository = $blacklistRepository;

        parent::__construct($context, $data);
    }

    public function getBlacklistItemQty()
    {
        $BlacklistItemQty = $this->getData('blacklist_qty');

        return $BlacklistItemQty;
    }
}