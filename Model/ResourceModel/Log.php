<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel;

class Log extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('alsoviewed_log', 'entity_id');
    }

    /**
     * @param  int $id      Product Id
     * @param  array $ids   Related Product Ids
     * @return $this
     */
    public function insertRelations($id, $ids)
    {
        $insertData = array();
        foreach ($ids as $relatedId) {
            // All relations are bidirectional, so I can use the min and max to
            // prevent duplicated relations in grouped by product_id columns query
            // @see getGroupedRelations method
            $insertData[] = array(
                'product_id'         => min($id, $relatedId),
                'related_product_id' => max($id, $relatedId)
            );
        }
        $this->getConnection()->insertMultiple(
            $this->getMainTable(), $insertData
        );
        return $this;
    }
}
