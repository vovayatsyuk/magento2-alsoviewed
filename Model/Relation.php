<?php

namespace Vovayatsyuk\Alsoviewed\Model;

use Magento\Framework\Model\AbstractModel;
use Vovayatsyuk\Alsoviewed\Api\Data\RelationInterface;

class Relation extends AbstractModel implements RelationInterface
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 0;

    /**
     * @var Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $products;

    /**
     * @var Vovayatsyuk\Alsoviewed\Model\Relation
     */
    protected $inversedRelation;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation');
        // $this->setData('id_field_name', 'relation_id');
    }

    /**
     * Retrieve inversed relation
     *
     * @return \Vovayatsyuk\Alsoviewed\Model\Relation
     */
    public function getInversedRelation()
    {
        if (!$this->inversedRelation) {
            $this->inversedRelation = $this->getCollection()
                ->addFieldToFilter('product_id', $this->getRelatedProductId())
                ->addFieldToFilter('related_product_id', $this->getProductId())
                ->getFirstItem();

            if (!$this->inversedRelation->getId()) {
                $this->inversedRelation
                    ->setProductId($this->getRelatedProductId())
                    ->setRelatedProductId($this->getProductId());
            }
        }
        return $this->inversedRelation;
    }

    /**
     * Load products that are linked within this relation
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts()
    {
        if (!$this->products) {
            $this->products = $this->productCollectionFactory->create()
                ->addAttributeToSelect('name')
                ->addFieldToFilter('entity_id', [
                    'in' => [
                        $this->getProductId(),
                        $this->getRelatedProductId()
                    ]
                ]);
        }
        return $this->products;
    }

    /**
     * Get product model by product_id
     *
     * @return Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->getProducts()->getItemById($this->getProductId());
    }

    /**
     * Get product model by related_product_id
     *
     * @return Magento\Catalog\Model\Product
     */
    public function getRelatedProduct()
    {
        return $this->getProducts()->getItemById($this->getRelatedProductId());
    }

    /**
     * Get relation title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getProduct()->getName() . ' - ' . $this->getRelatedProduct()->getName();
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::RELATION_ID);
    }

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Get related product ID
     *
     * @return int
     */
    public function getRelatedProductId()
    {
        return $this->getData(self::RELATED_PRODUCT_ID);
    }

    /**
     * Get relation weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->getData(self::WEIGHT);
    }

    /**
     * Get relation position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getStatus() === self::STATUS_ENABLED;
    }

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set ID
     *
     * @param  int $id
     * @return RelationInterface
     */
    public function setId($id)
    {
        return $this->setData(self::RELATION_ID, $id);
    }

    /**
     * Set product ID
     *
     * @param  int $id
     * @return RelationInterface
     */
    public function setProductId($id)
    {
        return $this->setData(self::PRODUCT_ID, $id);
    }

    /**
     * Set related product ID
     *
     * @param  int $id
     * @return RelationInterface
     */
    public function setRelatedProductId($id)
    {
        return $this->setData(self::RELATED_PRODUCT_ID, $id);
    }

    /**
     * Set relation weight
     *
     * @param  int $weight
     * @return RelationInterface
     */
    public function setWeight($weight)
    {
        return $this->setData(self::WEIGHT, $weight);
    }

    /**
     * Set relation position
     *
     * @param  int $position
     * @return RelationInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Set status
     *
     * @param  int $status
     * @return RelationInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Set update time
     *
     * @param  string $time
     * @return RelationInterface
     */
    public function setUpdatedAt($time)
    {
        return $this->setData(self::UPDATED_AT, $time);
    }

    /**
     * Prepare relation statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }
}
