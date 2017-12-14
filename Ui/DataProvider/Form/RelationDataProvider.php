<?php

namespace Vovayatsyuk\Alsoviewed\Ui\DataProvider\Form;

class RelationDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\Relation\Locator\LocatorInterface
     */
    protected $locator;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation\Collection $collection
     * @param \Vovayatsyuk\Alsoviewed\Model\Relation\Locator\LocatorInterface $locator
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation\Collection $collection,
        \Vovayatsyuk\Alsoviewed\Model\Relation\Locator\LocatorInterface $locator,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection;
        $this->locator = $locator;
    }

    public function getData()
    {
        $relation = $this->locator->getRelation();
        return [
            $relation->getId() => $relation->getData()
        ];
    }
}
