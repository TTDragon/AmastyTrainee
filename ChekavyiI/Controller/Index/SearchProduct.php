<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Controller\Index;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class SearchProduct implements HttpGetActionInterface
{
    public const PARAM_SKU = 'sku';
    public const MAX_ITEMS = 'max_items';
    public const MIN_LENGTH = 3;
    public const MAX_ITEMS_DEFAULT = 10;
    public const MAX_ITEMS_MAX = 100;
    public const MAX_ITEMS_MIN = 1;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        ResultFactory $resultFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        $sku = $this->request->getParam(self::PARAM_SKU, '');
        $maxItemsAmount = (int) $this->request->getParam(self::MAX_ITEMS, self::MAX_ITEMS_DEFAULT);
        $maxItemsAmount = max(self::MAX_ITEMS_MIN, min(self::MAX_ITEMS_MAX, $maxItemsAmount));
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if (strlen($sku) >= self::MIN_LENGTH) {
            $productCollection = $this->collectionFactory->create();
            $productCollection->addFieldToFilter('sku', ['like' => "{$sku}%"]);
            $productCollection->addAttributeToSelect(['name']);
            $productCollection->setPageSize($maxItemsAmount);
            $responseArray = [];

            foreach ($productCollection as $product) {
                $responseArray[] = [
                    'name' => $product->getName(),
                    'sku' => $product->getSku()
                ];
            }

            $result->setData($responseArray);
        } else {
            $result->setData(['message' => __('Empty SKU is not allowed')]);
            $result->setHttpResponseCode(406);
        }

        return $result;
    }
}
