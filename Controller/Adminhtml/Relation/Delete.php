<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Adminhtml\Relation;

use Vovayatsyuk\Alsoviewed\Model\RelationFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Vovayatsyuk_Alsoviewed::relation_delete';

    /**
     * @var RelationFactory
     */
    protected $relationFactory;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        RelationFactory $relationFactory
    ) {
        $this->relationFactory = $relationFactory;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $relation = $this->relationFactory->create()->load($id);
                if ($this->getRequest()->getParam('apply_to_inversed_relation')) {
                    if ($relation->getInversedRelation()->getId()) {
                        $relation->getInversedRelation()->delete();
                    }
                }
                $relation->delete();
                $this->messageManager->addSuccess(__('You deleted relation.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving relation.'));
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        $this->messageManager->addError(__('We can\'t find relation to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
