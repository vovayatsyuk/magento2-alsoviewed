<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Product;

use Magento\Framework\Controller\ResultFactory;

class ListAjax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    private $urlHelper;

    /**
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->urlHelper = $urlHelper;
        return parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            return $this->_redirect('/');
        }

        $this->_view->loadLayout();

        $block = $this->getLayout()->getBlock('alsoviewed.products');
        $block->addData($this->getRequest()->getParam('block'));

        $currentUrl = $this->urlHelper->getCurrentBase64Url();
        $refererUrl = $this->urlHelper->getEncodedUrl($this->getRequest()->getParam('referer'));
        $html = $this->getLayout()->renderElement('alsoviewed.products');
        $html = str_replace($currentUrl, $refererUrl, $html);

        return $this->resultFactory
            ->create(ResultFactory::TYPE_JSON)
            ->setData([
                'html' => $html,
            ]);
    }

    /**
     * @return \Magento\Framework\View\LayoutInterface
     */
    private function getLayout()
    {
        return $this->_view->getLayout();
    }
}
