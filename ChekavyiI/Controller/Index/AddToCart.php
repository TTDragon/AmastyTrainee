<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Controller\Index;

use Amasty\ChekavyiI\Model\BlacklistFactory;
use Amasty\ChekavyiI\Model\ResourceModel\Blacklist as ResourceBlacklist;
use Amasty\ChekavyiI\Model\ResourceModel\Blacklist\CollectionFactory as BlacklistCollectionFactory;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Checkout\Model\Cart as ModelCart;
use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
use Magento\Checkout\Model\SessionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class AddToCart implements HttpPostActionInterface
{
    public const PARAM_QTY = 'qty';
    public const PARAM_SKU = 'sku';

    /**
     * @var ModelCart
     */
    private ModelCart $modelCart;

    /**
     * @var ProductResource
     */
    private ProductResource $productResource;

    /**
     * @var BlacklistCollectionFactory
     */
    private BlacklistCollectionFactory $blacklistCollectionFactory;

    /**
     * @var EventManagerInterface
     */
    private EventManagerInterface  $eventManager;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Validator
     */
    private Validator $formKeyValidator;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @var RequestQuantityProcessor|null
     */
    private ?RequestQuantityProcessor $quantityProcessor;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var ResolverInterface
     */
    private ResolverInterface $resolver;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var SessionFactory
     */
    private SessionFactory $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    public function __construct(
        RequestInterface $request,
        Validator $formKeyValidator,
        ManagerInterface $messageManager,
        RequestQuantityProcessor $quantityProcessor,
        RedirectFactory $redirectFactory,
        ResolverInterface $resolver,
        ProductRepositoryInterface $productRepository,
        SessionFactory $checkoutSession,
        CartRepositoryInterface $cartRepository,
        EventManagerInterface $eventManager,
        BlacklistFactory $blacklistFactory,
        ResourceBlacklist $resourceBlacklist,
        BlacklistCollectionFactory $blacklistCollectionFactory,
        ProductResource $productResource,
        ModelCart $modelCart
    ) {
        $this->blacklistCollectionFactory = $blacklistCollectionFactory;
        $this->request = $request;
        $this->formKeyValidator = $formKeyValidator;
        $this->messageManager = $messageManager;
        $this->quantityProcessor = $quantityProcessor;
        $this->redirectFactory = $redirectFactory;
        $this->resolver = $resolver;
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->eventManager = $eventManager;
        $this->productResource = $productResource;
        $this->modelCart = $modelCart;
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create()->setPath('*/*/');

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(
                __('Your session has expired')
            );

            return $redirect;
        }

        $qty = $this->request->getParam(self::PARAM_QTY);
        $sku = $this->request->getParam(self::PARAM_SKU);

        if (empty($qty) || empty($sku)) {
            $this->messageManager->addErrorMessage(
                __('Please fill all required fields')
            );

            return $redirect;
        }

        $filter = new \Zend_Filter_LocalizedToNormalized(
            ['locale' => $this->resolver->getLocale()]
        );
        $qty = $this->quantityProcessor->prepareQuantity($qty);
        $qty = $filter->filter($qty);

        try {
            if (!empty($this->checkSkuInBlacklist($sku))){
                $blacklistQty = $this->checkSkuInBlacklist($sku);
                $productId = $this->productResource->getIdBySku($sku);
                $items = $this->modelCart->getQuote()->getAllItems();
                $productQtyInCart = 0;

                foreach ($items as $item) {
                    if ($item->getProductId() === $productId) {
                        $productQtyInCart = $item->getQty();
                    }
                }

                if ($qty + $productQtyInCart > $blacklistQty) {

                    if ($blacklistQty >= $productQtyInCart) {
                        $qty = $blacklistQty - $productQtyInCart;
                    }

                    $this->messageManager->addErrorMessage(__("It is possible to add only $qty products"));
                }
            }

            $product = $this->productRepository->get($sku);
            $productType = $product->getTypeId();

            if ($productType === Type::TYPE_SIMPLE) {
                $this->addProductToQuote($product, $qty);
                $this->eventManager->dispatch(
                    'amasty_add_product',
                    [
                        'customer_sku' => $sku
                    ]
                );
            } else {
                $this->messageManager->addErrorMessage(__('This product is not simple'));
            }

        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $redirect;
    }

    private function getRequest(): RequestInterface
    {
        return $this->request;
    }

    private function addProductToQuote($product, $qty)
    {
        $session = $this->checkoutSession->create();
        $quote = $session->getQuote();
        $quote->addProduct($product, $qty);
        $this->cartRepository->save($quote);
    }

    private function checkSkuInBlacklist($sku)
    {
        /** @var \Amasty\ChekavyiI\Model\ResourceModel\Blacklist\Collection $blacklistCollection */
        $blacklistCollection = $this->blacklistCollectionFactory->create();
        $blacklistCollection->addFieldToFilter('sku', ['eq' => $sku]);
        $qtuBlacklistSku = null;

        foreach ($blacklistCollection as $blaklistSku) {
            $qtuBlacklistSku = $blaklistSku->getQty();
        }

        return $qtuBlacklistSku;
    }
}
