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
     * @var \Magento\Framework\EntityManager\EntityManager
     */
    protected $entityManager;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Backend\App\ConfigInterface $backendConfig
     * @param \Magento\Framework\EntityManager\EntityManager $entityManager
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Backend\App\ConfigInterface $backendConfig,
        \Magento\Framework\EntityManager\EntityManager $entityManager,
        $connectionName = null
    ) {
        $this->backendConfig = $backendConfig;
        $this->entityManager = $entityManager;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('alsoviewed_relation', 'relation_id');
    }

    public function updateRelations($relationsData)
    {
        // create bidirectional relations
        $data = $relationsData;
        foreach ($relationsData as $relation) {
            $data[] = array(
                'product_id'         => $relation['related_product_id'],
                'related_product_id' => $relation['product_id'],
                'weight'             => $relation['weight']
            );
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

    /**
     * @inheritDoc
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
