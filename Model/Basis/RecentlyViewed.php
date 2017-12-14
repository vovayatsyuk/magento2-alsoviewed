<?php

namespace Vovayatsyuk\Alsoviewed\Model\Basis;

class RecentlyViewed implements BasisInterface
{
    /**
     * @var \Magento\Reports\Model\ResourceModel\Product\Index\Viewed\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param \Magento\Reports\Model\ResourceModel\Product\Index\Viewed\Collection $collection
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Reports\Model\ResourceModel\Product\Index\Viewed\Collection $collection,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->collection = $collection;
        $this->productVisibility = $productVisibility;
        $this->customerSession = $customerSession;
    }

    public function getIds()
    {
        return $this->collection
            ->addIndexFilter()
            ->setCustomerId($this->customerSession->getCustomerId())
            ->setVisibility($this->productVisibility->getVisibleInSiteIds())
            ->setAddedAtOrder()
            ->getColumnValues('entity_id');
    }
}
