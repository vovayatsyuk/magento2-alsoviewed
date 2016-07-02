<?php

namespace Vovayatsyuk\Alsoviewed\Model\Relation\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\Relation
     */
    protected $relation;

    /**
     * Constructor
     *
     * @param \Vovayatsyuk\Alsoviewed\Model\Relation $relation
     */
    public function __construct(\Vovayatsyuk\Alsoviewed\Model\Relation $relation)
    {
        $this->relation = $relation;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->relation->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
