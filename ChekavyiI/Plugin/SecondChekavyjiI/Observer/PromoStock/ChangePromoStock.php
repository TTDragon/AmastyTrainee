<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Plugin\SecondChekavyjiI\Observer\PromoStock;

use Amasty\SecondChekavyjiI\Observer\PromoStock;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;

class ChangePromoStock
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @param PromoStock $subject
     * @param callable $proceed
     * @param Observer $observer
     *
     * @return void
     */
    public function aroundExecute(PromoStock $subject, callable $proceed, Observer $observer)
    {
        return $this->request->isXmlHttpRequest() ? null : $proceed($observer);
    }
}