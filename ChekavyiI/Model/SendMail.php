<?php

declare(strict_types=1);

namespace Amasty\ChekavyiI\Model;

use Magento\Framework\App\ResponseInterface;
use Amasty\ChekavyiI\Model\BlacklistFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Amasty\ChekavyiI\Model\BlacklistRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Amasty\ChekavyiI\Model\ConfigProvider;
use Amasty\ChekavyiI\Model\ResourceModel\Blacklist as ResourceBlacklist;
use Magento\Framework\Mail\Template\FactoryInterface as TemplateFactoryInterface;
use Magento\Config\Model\Config\Source\Email\Template;

class SendMail
{
    private const TEMPLATE_ID = 'test_config_general_amasty_chekavyii_email_template';
    private const SENDER_NAME = 'Admin';
    private const SENDER_EMAIL = 'admin@gmail.com';

    /**
     * @var TemplateFactoryInterface
     */
    private TemplateFactoryInterface $templateFactory;

    /**
     * @var BlacklistFactory
     */
    private BlacklistFactory $blacklistFactory;

    /**
     * @var ResourceBlacklist
     */
    private ResourceBlacklist $resourceBlacklist;

    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var TransportBuilder
     */
    private TransportBuilder $transportBuilder;

    /**
     * @var BlacklistRepository
     */
    private BlacklistRepository $blacklistRepository;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        TransportBuilder $transportBuilder,
        BlacklistRepository $blacklistRepository,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ConfigProvider $configProvider,
        BlacklistFactory $blacklistFactory,
        ResourceBlacklist $resourceBlacklist,
        TemplateFactoryInterface $templateFactory
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->blacklistRepository = $blacklistRepository;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
        $this->blacklistFactory = $blacklistFactory;
        $this->resourceBlacklist = $resourceBlacklist;
        $this->templateFactory = $templateFactory;
    }

    public function sendEmail()
    {
        $emailAddress = $this->configProvider->getEmailAddress();
        $blacklistItem = $this->blacklistRepository->getById(9);
        $vars = [
            'blacklist_sku' => $blacklistItem->getSku(),
            'blacklist_qty' => $blacklistItem->getQty(),
        ];
        $sender = [
            'name' => self::SENDER_NAME,
            'email' => self::SENDER_EMAIL,
        ];
        $options = [
            'area' => Area::AREA_FRONTEND,
            'store' => $this->storeManager->getStore()->getId(),
        ];

        $this->transportBuilder->setTemplateIdentifier(
            self::TEMPLATE_ID
        )->setTemplateOptions(
            $options
        )->setTemplateVars(
            $vars
        )->setFromByScope(
           $sender
        )->addTo($emailAddress);
        $transport = $this->transportBuilder->getTransport();

        $blacklist = $this->blacklistFactory->create();
        $emailBody = $transport->getMessage()->getBody()->generateMessage();
        $blacklist->setEmailBody($emailBody);
        $this->resourceBlacklist->save($blacklist);

        $transport->sendMessage();
    }
}
