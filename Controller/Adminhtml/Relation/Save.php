<?php

namespace Vovayatsyuk\Alsoviewed\Controller\Adminhtml\Relation;

use Vovayatsyuk\Alsoviewed\Model\RelationFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Vovayatsyuk_Alsoviewed::relation_save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

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
        RelationFactory $relationFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->relationFactory = $relationFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('relation_id');

            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Relation::STATUS_ENABLED;
            }
            if (empty($data['relation_id'])) {
                $data['relation_id'] = null;
            }

            /** @var \Vovayatsyuk\Alsoviewed\Model\Relation $model */
            $relation = $this->relationFactory->create()->load($id);
            if (!$relation->getId() && $id) {
                $this->messageManager->addError(__('This relation no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $relation->addData($data);

            try {
                $relation->save();
                if ($this->getRequest()->getParam('apply_to_inversed_relation')) {
                    $relation->getInversedRelation()
                        ->addData([
                            'weight'   => $data['weight'],
                            'position' => $data['position'],
                            'status'   => $data['status']
                        ])
                        ->save();
                }
                $this->messageManager->addSuccess(__('You saved relation.'));
                $this->dataPersistor->clear('alsoviewed_relation');
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving relation.'));
            }

            $this->dataPersistor->set('alsoviewed_relation', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
