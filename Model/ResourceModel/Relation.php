<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel;

class Relation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('alsoviewed_relation', 'relation_id');
    }
}
