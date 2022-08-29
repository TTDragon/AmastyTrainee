<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Amasty\ChekavyiI\Model\ResourceModel\Blacklist as ResourceBlacklist;
use Amasty\ChekavyiI\Model\BlacklistFactory;

class InstallBlacklist implements DataPatchInterface
{
    /**
     * @var BlacklistFactory
     */
    private BlacklistFactory $blacklistFactory;

    /**
     * @var ResourceBlacklist
     */
    private ResourceBlacklist $resourceBlacklist;

    public function __construct(
        BlacklistFactory $blacklistFactory,
        ResourceBlacklist $resourceBlacklist
    ) {
        $this->blacklistFactory = $blacklistFactory;
        $this->resourceBlacklist = $resourceBlacklist;
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
        $skus = [
            '24-MB01' => 100,
            '24-MB02' => 100,
            '24-MB03' => 100,
            '24-MB04' => 100,
            '24-MB05' => 100
        ];

        foreach ($skus as $sku => $qty) {
            $blacklist = $this->blacklistFactory->create();
            $blacklist->setSku($sku);
            $blacklist->setQty($qty);
            $this->resourceBlacklist->save($blacklist);
        }
    }
}
