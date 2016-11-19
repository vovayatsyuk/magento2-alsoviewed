<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel\Collection;

abstract class AbstractCollection
    extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    implements CollectionInterface
{
    protected $_map = ['fields' => [
        'entity_id'            => 'main_table.entity_id',
        'product_name'         => 'product_name.value',
        'related_product_name' => 'related_product_name.value'
    ]];

    protected $attributeCollectionFactory;

    protected $productNameAttributeId;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    public function addProductNameToSelect($alias = 'product_name', $column = 'product_id')
    {
        $this->getSelect()->join(
                [$alias => $this->getProductNameTableName()],
                "{$column} = {$alias}.entity_id",
                [$alias => 'value']
            )
            ->where("{$alias}.store_id = ?", 0)
            ->where("{$alias}.attribute_id = ?", $this->getProductNameAttributeId());

        return $this;
    }

    public function addRelatedProductNameToSelect()
    {
        return $this->addProductNameToSelect('related_product_name', 'related_product_id');
    }

    protected function getProductNameTableName()
    {
        return $this->getConnection()->getTableName('catalog_product_entity_varchar');
    }

    protected function getProductNameAttributeId()
    {
        if (!$this->productNameAttributeId) {
            $collection = $this->attributeCollectionFactory->create();
            $this->productNameAttributeId = $collection
                ->addFieldToFilter('attribute_code', 'name')
                ->getFirstItem()
                ->getId();
        }
        return $this->productNameAttributeId;
    }
}
