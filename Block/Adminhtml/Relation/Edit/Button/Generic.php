<?php

namespace Vovayatsyuk\Alsoviewed\Block\Adminhtml\Relation\Edit\Button;

use Vovayatsyuk\Alsoviewed\Model\Relation\Locator\LocatorInterface;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Generic implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Locator
     */
    protected $locator;

    /**
     * Generic constructor
     *
     * @param Context $context
     */
    public function __construct(
        Context $context,
        LocatorInterface $locator
    ) {
        $this->context = $context;
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [];
    }

    /**
     * Return Relation ID
     *
     * @return int|null
     */
    public function getRelationId()
    {
        if ($relation = $this->getRelation()) {
            return $relation->getId();
        }
        return null;
    }

    /**
     * Return Relation
     *
     * @return \Vovayatsyuk\Alsoviewed\Model\Relation|null
     */
    public function getRelation()
    {
        return $this->locator->getRelation();
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrl($route, $params);
    }
}
