<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Amasty\ChekavyiI\Model\BlacklistFactory;

class InstallBlacklist implements DataPatchInterface
{
    /**
     * @var BlacklistFactory
     */
    private BlacklistFactory $blacklistFactory;

    public function __construct(
        BlacklistFactory $blacklistFactory
    ) {
        $this->blacklistFactory = $blacklistFactory;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        // TODO: Implement apply() method.
    }
}