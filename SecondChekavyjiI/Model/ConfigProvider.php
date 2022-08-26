<?php

declare(strict_types=1);

namespace Amasty\SecondChekavyjiI\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    public const XML_PATH_IS_MODULE_ENABLED = 'test2_config/general2/enabled';
    public const XML_PATH_PROMO_SKU = 'test2_config/general2/promosku';
    public const XML_PATH_FOR_SKU = 'test2_config/general2/forsku';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isModuleEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::XML_PATH_IS_MODULE_ENABLED);
    }

    public function getPromoSku(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_PROMO_SKU);
    }

    public function getValueForSkus(): array
    {
        $forSkusWithoutSpace = $this->scopeConfig->getValue(self::XML_PATH_FOR_SKU);
        $forSkus = explode(',', $forSkusWithoutSpace);
        $forSkus = array_map('trim', $forSkus);

        return $forSkus;
    }
}