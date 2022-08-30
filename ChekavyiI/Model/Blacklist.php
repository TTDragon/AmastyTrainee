<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Model;

use Amasty\ChekavyiI\Model\ResourceModel\Blacklist as ResourceBlacklist;
use Magento\Framework\Model\AbstractModel;

class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceBlacklist::class);

        parent::_construct();
    }
}
