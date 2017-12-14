<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel\Product;

class CollectionFactory
{
    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param mixed $basis Array or string
     * @param array $data
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function create($basisProductIds, array $data = [])
    {
        $collection = $this->productCollectionFactory->create()
            ->joinTable(
                ['alsoviewed' => 'alsoviewed_relation'],
                'related_product_id=entity_id',
                [
                    'alsoviewed_weight'   => 'weight',
                    'alsoviewed_position' => 'position',
                ],
                [
                    'product_id' => ['in' => $basisProductIds],
                    'status'     => 1
                ],
                'inner'
            )
            ->addAttributeToSort('alsoviewed_position', 'ASC')
            ->addAttributeToSort('alsoviewed_weight', 'DESC');

        if (count($basisProductIds) > 1) {
            $collection->addAttributeToFilter('entity_id', ['nin' => $basisProductIds]);
            // prevent "Item with the same id already exist" error
            $collection->getSelect()->group('e.entity_id');
        }

        return $collection;
    }
}
