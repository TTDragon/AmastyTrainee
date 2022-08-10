<?php

namespace Amasty\ChekavyiI\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    public const XML_PATH_IS_MODULE_ENABLED = 'general/enabled';
    public const XML_PATH_IS_WELCOME_TEXT = 'general/welcometext';
    public const XML_PATH_IS_QTY_ENABLED = 'general/qtyenabled';

    protected string $pathPrefix = 'test_config';

    public function isModuleEnabled(): bool
    {
      return (bool) $this->getValue(self::XML_PATH_IS_MODULE_ENABLED);
    }

    public function getWelcomeText(): string
    {
        return (string) $this->getValue(self::XML_PATH_IS_WELCOME_TEXT);
    }

    public function isFieldQtyEnabled(): bool
    {
        return (bool) $this->getValue(self::XML_PATH_IS_QTY_ENABLED);
    }
}
