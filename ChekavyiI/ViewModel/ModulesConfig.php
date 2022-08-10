<?php

namespace Amasty\ChekavyiI\ViewModel;

use Amasty\ChekavyiI\Model\ConfigProvider;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ModulesConfig implements ArgumentInterface
{
    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    public function isModuleEnabled(): bool
    {
        return $this->configProvider->isModuleEnabled();
    }

    public function getWelcomeText(): string
    {
        return (string) $this->configProvider->getWelcomeText();
    }

    public function isFieldQtyEnabled(): bool
    {
        return $this->configProvider->isFieldQtyEnabled();
    }
}