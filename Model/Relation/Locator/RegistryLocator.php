<?php

namespace Vovayatsyuk\Alsoviewed\Model\Relation\Locator;

use Vovayatsyuk\Alsoviewed\Model\Relation;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;

class RegistryLocator implements LocatorInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\Relation
     */
    private $relation;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     * @throws NotFoundException
     */
    public function getRelation()
    {
        if (null !== $this->relation) {
            return $this->relation;
        }

        if ($relation = $this->registry->registry('alsoviewed_relation')) {
            return $this->relation = $relation;
        }

        throw new NotFoundException(__('Relation was not registered'));
    }
}
