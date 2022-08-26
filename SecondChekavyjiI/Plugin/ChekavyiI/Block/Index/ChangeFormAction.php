<?php

declare(strict_types=1);

namespace Amasty\SecondChekavyjiI\Plugin\ChekavyiI\Block\Index;

use Amasty\ChekavyiI\Block\Index;

class ChangeFormAction
{
    private const CART_FORM_ACTION = 'checkout/cart/add';

    public function afterGetFormAction(Index $subject, string $result): string
    {
        return self::CART_FORM_ACTION;
    }
}
