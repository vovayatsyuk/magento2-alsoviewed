<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel;

class Relation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const CONFIG_PATH_CHUNK_SIZE = 'alsoviewed/perfomance/chunk_size';

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    protected $backendConfig;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Backend\App\ConfigInterface $backendConfig
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Backend\App\ConfigInterface $backendConfig,
        $connectionName = null
    ) {
        $this->backendConfig = $backendConfig;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('alsoviewed_relation', 'relation_id');
    }

    /**
     * Perform actions before object save
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setUpdatedAt(time());
        return $this;
    }

    public function updateRelations($relationsData)
    {
        // create bidirectional relations
        $data = $relationsData;
        foreach ($relationsData as $relation) {
            $data[] = [
                'product_id'         => $relation['related_product_id'],
                'related_product_id' => $relation['product_id'],
                'weight'             => $relation['weight']
            ];
        }

        $size = $this->backendConfig->getValue(self::CONFIG_PATH_CHUNK_SIZE);
        $connection = $this->getConnection();
        foreach (array_chunk($data, $size) as $chunkedData) {
            $connection->insertOnDuplicate(
                $this->getMainTable(),
                $chunkedData,
                [
                    'weight' => new \Zend_Db_Expr('weight + VALUES(weight)')
                ]
            );
        }
    }
}
