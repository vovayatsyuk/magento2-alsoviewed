<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel\Log;

class Collection extends \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Magento\Framework\DataObject', 'Vovayatsyuk\Alsoviewed\Model\ResourceModel\Log');
    }
}
