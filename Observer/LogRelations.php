<?php

namespace Vovayatsyuk\Alsoviewed\Observer;

class LogRelations implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $session;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\LogFactory
     */
    protected $logFactory;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Vovayatsyuk\Alsoviewed\Model\ResourceModel\LogFactory $logFactory
     * @param \Vovayatsyuk\Alsoviewed\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Vovayatsyuk\Alsoviewed\Model\ResourceModel\LogFactory $logFactory,
        \Vovayatsyuk\Alsoviewed\Helper\Data $helper
    ) {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
        $this->logFactory = $logFactory;
        $this->helper = $helper;
    }

    /**
     * Save product relations to log table
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->canIgnoreRequest()) {
            return;
        }

        $productId = $observer->getControllerAction()->getRequest()->getParam('id');
        $viewedIds = $this->getRecentlyViewedProductIds();

        if ($productId && !in_array($productId, $viewedIds)) {
            if (count($viewedIds)) {
                $this->logFactory->create()->insertRelations($productId, $viewedIds);
            }
            $this->addRecentlyViewedProductId($productId);
        }
    }

    /**
     * Add product id to the session stack
     *
     * @param integer $id Product ID
     */
    protected function addRecentlyViewedProductId($id)
    {
        $ids = $this->getRecentlyViewedProductIds();
        $limit = $this->scopeConfig->getValue('alsoviewed/session/limit');
        $offset = count($ids) - $limit;
        if ($offset > 0) {
            $ids = array_slice($ids, $offset);
        }
        $ids[] = $id;
        $this->session->setAlsoviewedProductIds($ids);
        return $this;
    }

    /**
     * Get recently viewed product ids array
     *
     * @return array
     */
    protected function getRecentlyViewedProductIds()
    {
        $ids = $this->session->getAlsoviewedProductIds();
        if (!$ids) {
            return [];
        }
        return $ids;
    }

    /**
     * Returns is request can be ignored
     *
     * @return boolean
     */
    protected function canIgnoreRequest()
    {
        return $this->helper->isUserAgentIgnored()
            || $this->helper->isIpAddressIgnored();
    }
}
