<?php

declare(strict_types=1);

namespace Amasty\SecondChekavyjiI\Model\ChekavyiI\Controller\Index\Index;

use Amasty\ChekavyiI\Controller\Index\Index;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\ResultFactory;

class ChangeIndexPhp extends Index
{
    private const HTTP_NOT_FOUND = 404;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    /**
     * @var CustomerSession
     */
    private CustomerSession $customerSession;

    public function __construct(
        ResultFactory $resultFactory,
        CustomerSession $customerSession
    ) {
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;

        parent::__construct($resultFactory);
    }

    public function execute()
    {
        if($this->customerSession->isLoggedIn()) {
            return parent::execute();
        } else {
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setHttpResponseCode(self::HTTP_NOT_FOUND);

            return $result;
        }
    }
}
