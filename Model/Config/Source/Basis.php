<?php

namespace Vovayatsyuk\Alsoviewed\Model\Config\Source;

class Basis implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'current',  'label' => __('Currently Viewed Product')],
            ['value' => 'viewed',   'label' => __('Recently Viewed Products')],
            ['value' => 'compared', 'label' => __('Recently Compared Products')],
            ['value' => 'cart',     'label' => __('Shopping Cart Items')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this->toOptionArray() as $values) {
            $result[$values['value']] = $values['label'];
        }
        return $result;
    }
}
