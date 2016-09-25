<?php

namespace Vovayatsyuk\Alsoviewed\Model;

class BasisCollector
{
    const DEFAULT_BASIS_LIMIT = 10;

    const BASIS_CURRENT_PRODUCT     = 'current';
    const BASIS_RECENTLY_VIEWED     = 'viewed';
    const BASIS_RECENTLY_COMPARED   = 'compared';
    const BASIS_SHOPPING_CART       = 'cart';

    /**
     * @var array
     */
    protected $basisClasses = [
        self::BASIS_CURRENT_PRODUCT     => '\Vovayatsyuk\Alsoviewed\Model\Basis\CurrentProduct',
        self::BASIS_RECENTLY_VIEWED     => '\Vovayatsyuk\Alsoviewed\Model\Basis\RecentlyViewed',
        self::BASIS_RECENTLY_COMPARED   => '\Vovayatsyuk\Alsoviewed\Model\Basis\RecentlyCompared',
        self::BASIS_SHOPPING_CART       => '\Vovayatsyuk\Alsoviewed\Model\Basis\ShoppingCart',
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    public function collect($codes)
    {
        if (!is_array($codes)) {
            $codes = [$codes];
        }

        $ids = [];
        foreach ($codes as $code) {
            if (!isset($this->basisClasses[$code])) {
                throw new \InvalidArgumentException("{$code} is not a valid basis");
            }
            // @TODO make mode much more flexibe (create logic rules):
            //  current|compared|viewed,cart - use current|if none was found - use compared|and so on...
            //  compared:cart|cart|viewed - items from cart that where compared recently|cart (if none was found)| viewed (if none was found)
            //  compared,cart|viewed - items from cart and compared items|viewed if none was found

            // @TODO collect ids by groups and then slice each group proportionally
            // instead of slicing whole array in the end.
            $basisModel = $this->objectManager->create($this->basisClasses[$code]);
            $ids = array_merge($ids, $basisModel->getIds());
        }
        $ids = array_unique($ids);

        if (count($ids) > self::DEFAULT_BASIS_LIMIT) {
            $ids = array_slice($ids, -self::DEFAULT_BASIS_LIMIT);
        }
        return $ids;
    }
}
