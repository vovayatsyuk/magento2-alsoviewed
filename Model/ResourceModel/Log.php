<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel;

class Log extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('alsoviewed_log', 'entity_id');
    }

    /**
     * @param  int $id Product Id
     * @param  array $ids Related Product Ids
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function insertRelations($id, $ids)
    {
        $insertData = [];
        foreach ($ids as $relatedId) {
            // All relations are bidirectional, so I can use the min and max to
            // prevent duplicated relations in grouped by product_id columns query
            // @see getGroupedRelations method
            $insertData[] = [
                'product_id'         => min($id, $relatedId),
                'related_product_id' => max($id, $relatedId)
            ];
        }
        $this->getConnection()->insertMultiple(
            $this->getMainTable(), $insertData
        );
        return $this;
    }

    /**
     * Retrieve product relations with weight
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGroupedRelations()
    {
        $this->cleanOrphans();

        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), [
                'product_id',
                'related_product_id',
                'weight' => 'COUNT(entity_id)'
            ])
            ->group(['product_id', 'related_product_id']);

        return $this->getConnection()->fetchAll($select);
    }

    /**
     * Remove orphan records
     *
     * @return int Number of affected rows
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cleanOrphans()
    {
        $tableName = $this->getTable('catalog_product_entity');
        $select  = $this->getConnection()->select();

        $select->from($this->getMainTable(), 'entity_id')
            ->joinLeft(
                ['e' => $tableName],
                'product_id = e.entity_id',
                []
            )
            ->orWhere('e.entity_id IS NULL');

        $select->joinLeft(
                ['e2' => $tableName],
                'related_product_id = e2.entity_id',
                []
            )
            ->orWhere('e2.entity_id IS NULL');

        $ids = $this->getConnection()->fetchCol($select);
        if (!$ids) {
            return 0;
        }
        return $this->clean(['entity_id IN (?)' => $ids]);
    }

    /**
     * Remove matched records
     *
     * @param  string $where
     * @return int Number of affected rows
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function clean($where = '')
    {
        return $this->getConnection()->delete($this->getMainTable(), $where);
    }
}
