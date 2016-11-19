<?php

namespace Vovayatsyuk\Alsoviewed\Model\Config\Source;

class Listingmode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'grid', 'label' => __('Grid')],
            ['value' => 'list', 'label' => __('List')],
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
