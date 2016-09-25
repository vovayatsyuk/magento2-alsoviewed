<?php

namespace Vovayatsyuk\Alsoviewed\Model\Basis;

class CurrentProduct implements BasisInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->registry = $registry;
    }

    public function getIds()
    {
        $product = $this->registry->registry('current_product');;
        if ($product) {
            return [$product->getId()];
        }
        return [];
    }
}
