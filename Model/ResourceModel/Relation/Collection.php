<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation;

class Collection extends \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'relation_id';

    protected function _construct()
    {
        $this->_init(
            'Vovayatsyuk\Alsoviewed\Model\Relation',
            'Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation'
        );
    }
}
