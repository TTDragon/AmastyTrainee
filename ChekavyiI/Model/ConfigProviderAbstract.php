<?php

namespace Amasty\ChekavyiI\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

abstract class ConfigProviderAbstract
{
    private ScopeConfigInterface $scopeConfig;

    protected string $pathPrefix;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    private function getPathPrefix(): string
    {
        if (empty($this->pathPrefix)) {
            $className = str_replace('\Interceptor', '', static::class);

            throw new LocalizedException(__('%1::$pathPrefix property should be initialized', $className));
        }

        return rtrim($this->pathPrefix, '/');
    }

    protected function getValue(
        string $path,
        $scopeId = null,
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ): ?string {
        return $this->scopeConfig->getValue($this->getPathPrefix() . '/' . $path, $scope, $scopeId);
    }
}