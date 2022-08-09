<?php

namespace Amasty\ChekavyiI\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
    private ResultFactory $resultFactory;

    public function __construct(
        ResultFactory $resultFactory
    ) {
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        die('Привет Magento. Привет Amasty. Я готов тебя покорить!');
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
