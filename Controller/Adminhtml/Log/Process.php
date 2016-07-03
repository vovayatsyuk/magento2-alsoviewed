<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Adminhtml\Log;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Vovayatsyuk\Alsoviewed\Model\ResourceModel\Log;
use Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation;

class Process extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Vovayatsyuk_Alsoviewed::log';

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Log
     */
    protected $log;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation
     */
    protected $relation;

    /**
     * @param Context $context
     * @param Log $log
     * @param Relation $relation
     */
    public function __construct(
        Context $context,
        Log $log,
        Relation $relation
    ) {
        $this->log = $log;
        $this->relation = $relation;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $data = $this->log->getGroupedRelations();
        if ($data) {
            try {
                $this->relation->updateRelations($data);
                $this->log->clean();
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been updated.', count($data))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
