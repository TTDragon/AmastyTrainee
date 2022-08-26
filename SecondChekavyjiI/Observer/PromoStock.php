<?php

namespace Amasty\SecondChekavyjiI\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Amasty\SecondChekavyjiI\Model\ConfigProvider;
use Magento\Checkout\Model\SessionFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Exception;

class PromoStock implements ObserverInterface
{
    public const PROMO_SKU_QTY = 1;

    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @var SessionFactory
     */
    private SessionFactory $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    public function __construct(
        ConfigProvider $configProvider,
        SessionFactory $checkoutSession,
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager
    ) {
        $this->configProvider = $configProvider;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        if (!empty($observer->getCustomerSku())) {
            $forSkus = $this->configProvider->getValueForSkus();
            $promoSku = $this->configProvider->getPromoSku();

            try {
                $product = $this->productRepository->get($promoSku);

                foreach ($forSkus as $forSkuElement) {
                    if ($observer->getCustomerSku() === $forSkuElement) {
                        $session = $this->checkoutSession->create();
                        $quote = $session->getQuote();
                        $quote->addProduct($product, self::PROMO_SKU_QTY);
                        $this->cartRepository->save($quote);
                    }
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
    }
}
