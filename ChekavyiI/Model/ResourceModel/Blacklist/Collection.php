<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Model\ResourceModel\Blacklist;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Amasty\ChekavyiI\Model\Blacklist;
use Amasty\ChekavyiI\Model\ResourceModel\Blacklist as ResourceBlacklist;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Blacklist::class, ResourceBlacklist::class);

        parent::_construct();
    }
}