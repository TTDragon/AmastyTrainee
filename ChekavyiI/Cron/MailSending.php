<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Cron;

use Amasty\ChekavyiI\Model\SendMail;

class MailSending
{
    /**
     * @var SendMail
     */
    private SendMail $sendMail;

    public function __construct(
        SendMail $sendMail
    ) {
        $this->sendMail = $sendMail;
    }

    public function execute()
    {
        $this->sendMail->sendEmail();
    }
}