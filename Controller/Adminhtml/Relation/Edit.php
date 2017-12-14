<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Adminhtml\Relation;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Vovayatsyuk_Alsoviewed::relation';

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\RelationFactory
     */
    protected $relationFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Vovayatsyuk\Alsoviewed\Model\RelationFactory $relationFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Vovayatsyuk\Alsoviewed\Model\RelationFactory $relationFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->relationFactory = $relationFactory;
        $this->registry = $registry;
    }

    /**
     * Edit relation
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $relation = $this->relationFactory->create();

        if ($id) {
            $relation->load($id);
            if (!$relation->getId()) {
                $this->messageManager->addError(__('This relation no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->registry->register('alsoviewed_relation', $relation);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vovayatsyuk_Alsoviewed::alsoviewed_relation')
            ->addBreadcrumb(__('Also Viewed Products'), __('Also Viewed Products'))
            ->addBreadcrumb(__('Relations'), __('Relations'))
            ->addBreadcrumb(
                $id ? __('Edit Relation') : __('New Relation'),
                $id ? __('Edit Relation') : __('New Relation')
            );
        $resultPage->getConfig()->getTitle()->prepend(__('Relations'));
        $resultPage->getConfig()->getTitle()->prepend($id ? $relation->getTitle() : __('New Relation'));
        return $resultPage;
    }
}
