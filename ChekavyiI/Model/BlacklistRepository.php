<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Model;

use Amasty\ChekavyiI\Model\Blacklist;
use Amasty\ChekavyiI\Model\BlacklistFactory;
use Amasty\ChekavyiI\Model\ResourceModel\Blacklist as ResourceBlacklist;

class BlacklistRepository
{
    /**
     * @var BlacklistFactory
     */
    private BlacklistFactory $blacklistFactory;

    /**
     * @var ResourceBlacklist
     */
    private ResourceBlacklist $blacklistResource;

    public function __construct(
        ResourceBlacklist $blacklistResource,
        BlacklistFactory $blacklistFactory
    ) {
       $this->blacklistResource = $blacklistResource;
       $this->blacklistFactory = $blacklistFactory;
    }

    public function getById(int $blacklistSkuId): Blacklist
    {
        $blacklistItem = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklistItem, $blacklistSkuId);

        return $blacklistItem;
    }

    public function save(Blacklist $blacklistItem): Blacklist
    {
        $this->blacklistResource->save($blacklistItem);

        return $blacklistItem;
    }

    public function deleteById(Blacklist $blacklistSkuId): void
    {
        $blacklistItem = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklistItem, $blacklistSkuId);
        $this->blacklistResource->delete($blacklistItem);
    }

    public function setBlacklistEntry($sku, $qty)
    {
        $blacklistItem = $this->blacklistFactory->create();
        $blacklistItem->setSku($sku);
        $blacklistItem->setQty($qty);
        $this->blacklistResource->save($blacklistItem);
    }
}