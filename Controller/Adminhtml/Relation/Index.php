<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Adminhtml\Relation;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Vovayatsyuk_Alsoviewed::relation';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Alsoviewed products log page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vovayatsyuk_Alsoviewed::alsoviewed_relation')
            ->addBreadcrumb(__('Also Viewed Products'), __('Also Viewed Products'))
            ->addBreadcrumb(__('Relations'), __('Relations'));
        $resultPage->getConfig()->getTitle()->prepend(__('Relations'));
        return $resultPage;
    }
}
