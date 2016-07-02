<?php

namespace Vovayatsyuk\Alsoviewed\Model\ResourceModel\Collection;

interface CollectionInterface
{
    /**
     * Join product name table to collection by product_id
     *
     * @return $this
     */
    public function addProductNameToSelect();

    /**
     * Join product name table to collection by related_product_id
     *
     * @return $this
     */
    public function addRelatedProductNameToSelect();
}
