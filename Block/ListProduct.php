<?php

namespace Vovayatsyuk\Alsoviewed\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * Basis collector
     *
     * @var \Vovayatsyuk\Alsoviewed\Model\BasisCollector
     */
    protected $basisCollector;

    /**
     * Product collection factory
     *
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    protected $toolbarBlock;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Vovayatsyuk\Alsoviewed\Model\BasisCollector $basisCollector
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Vovayatsyuk\Alsoviewed\Model\BasisCollector $basisCollector,
        \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->basisCollector = $basisCollector;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {

            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
            $collection = $this->productCollectionFactory->create(
                $this->basisCollector->collect($this->getBasis())
            );
            $this->_catalogLayer->prepareProductCollection($collection);
            $collection->addStoreFilter();

            $toolbar = $this->getToolbarBlock();
            $toolbar->setData('_current_limit', $this->getData('products_count'));
            $toolbar->setData('_current_grid_mode', $this->getData('mode'));
            $toolbar->setCollection($collection);
            $this->setChild('toolbar', $toolbar);
            $this->_eventManager->dispatch(
                'catalog_block_product_list_collection',
                ['collection' => $collection]
            );

            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    protected function getBasis()
    {
        $basis = $this->getData('basis');
        if (null === $basis) {
            $basis = 'current';
        } elseif (!is_array($basis)) {
            $basis = explode(',', $basis);
        }
        return $basis;
    }

    public function getToolbarBlock()
    {
        if (null === $this->toolbarBlock) {
            $this->toolbarBlock = parent::getToolbarBlock();
        }
        return $this->toolbarBlock;
    }

    protected function _beforeToHtml()
    {
        // Do not call parent! Parent logic is copied to prepareToolbar method.
        // It was done to allow to get collection without block rendering.
    }

    public function getToolbarHtml()
    {
        return '';
    }
}
