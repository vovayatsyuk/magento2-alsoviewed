<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Product;

use Magento\Framework\Controller\ResultFactory;

class Log extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    private $session;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\LogFactory
     */
    private $logFactory;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Helper\Data
     */
    private $helper;

    /**
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Vovayatsyuk\Alsoviewed\Model\ResourceModel\LogFactory $logFactory,
        \Vovayatsyuk\Alsoviewed\Helper\Data $helper,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->session = $session;
        $this->logFactory = $logFactory;
        $this->helper = $helper;

        return parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        if ($this->canIgnoreRequest()) {
            return $this->_redirect('/');
        }

        $productId = $this->getRequest()->getParam('id');
        $viewedIds = $this->getRecentlyViewedProductIds();

        if ($productId && !in_array($productId, $viewedIds)) {
            if (count($viewedIds)) {
                $this->logFactory->create()->insertRelations($productId, $viewedIds);
            }
            $this->addRecentlyViewedProductId($productId);
        }

        return $this->resultFactory
            ->create(ResultFactory::TYPE_JSON)
            ->setData([]);
    }

    /**
     * Add product id to the session stack
     *
     * @param integer $id Product ID
     */
    protected function addRecentlyViewedProductId($id)
    {
        $ids = $this->getRecentlyViewedProductIds();
        $limit = $this->helper->getLogLimit();
        $offset = count($ids) - $limit;

        if ($offset > 0) {
            $ids = array_slice($ids, $offset);
        }

        $ids[] = $id;
        $this->session->setAlsoviewedProductIds($ids);
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
     * @return boolean
     */
    protected function canIgnoreRequest()
    {
        return !$this->getRequest()->isAjax()
            || !$this->getRequest()->isPost()
            || $this->helper->isUserAgentIgnored()
            || $this->helper->isIpAddressIgnored();
    }
}
