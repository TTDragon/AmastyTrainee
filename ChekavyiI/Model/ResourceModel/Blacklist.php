<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Amasty\ChekavyiI\Model\BlacklistFactory;

class Blacklist extends AbstractDb
{
    public const TABLE_NAME = 'amasty_chekavyii_blacklist';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'blacklist_sku_id');
    }
}
