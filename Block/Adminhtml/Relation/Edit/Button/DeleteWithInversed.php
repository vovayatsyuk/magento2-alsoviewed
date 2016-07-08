<?php

namespace Vovayatsyuk\Alsoviewed\Block\Adminhtml\Relation\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteWithInversed extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getRelationId()) {
            $data = [
                'label' => __('Delete with inversed relation'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 21
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', [
            'id' => $this->getRelationId(),
            'apply_to_inversed_relation' => 1
        ]);
    }
}
