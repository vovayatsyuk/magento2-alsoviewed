<?php

namespace Vovayatsyuk\Alsoviewed\Ui\DataProvider;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;

class AddProductNameFieldToCollection implements AddFieldToCollectionInterface
{
    public function addField(Collection $collection, $field, $alias = null)
    {
        /** @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Collection\AbstractCollection $collection */
        $collection->addProductNameToSelect();
    }
}
