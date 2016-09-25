<?php

namespace Vovayatsyuk\Alsoviewed\Model\Basis;

class ShoppingCart implements BasisInterface
{
    /**
     * @var \Magento\Checkout\Model\Cart $cart
     */
    protected $cart;

    /**
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct(
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->cart = $cart;
    }

    public function getIds()
    {
        return $this->cart->getProductIds();
    }
}
