<?php

namespace Vovayatsyuk\Alsoviewed\Api\Data;

interface RelationInterface
{
    const RELATION_ID           = 'relation_id';
    const PRODUCT_ID            = 'product_id';
    const RELATED_PRODUCT_ID    = 'related_product_id';
    const WEIGHT                = 'weight';
    const POSITION              = 'position';
    const STATUS                = 'status';
    const UPDATED_AT            = 'updated_at';

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId();

    /**
     * Get related product ID
     *
     * @return int
     */
    public function getRelatedProductId();

    /**
     * Get relation weight
     *
     * @return int
     */
    public function getWeight();

    /**
     * Get relation position
     *
     * @return int
     */
    public function getPosition();

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus();

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive();

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set ID
     *
     * @param  int $id
     * @return RelationInterface
     */
    public function setId($id);

    /**
     * Set product ID
     *
     * @param  int $id
     * @return RelationInterface
     */
    public function setProductId($id);

    /**
     * Set related product ID
     *
     * @param  int $id
     * @return RelationInterface
     */
    public function setRelatedProductId($id);

    /**
     * Set relation weight
     *
     * @param  int $weight
     * @return RelationInterface
     */
    public function setWeight($weight);

    /**
     * Set relation position
     *
     * @param  int $position
     * @return RelationInterface
     */
    public function setPosition($position);

    /**
     * Set status
     *
     * @param  int $status
     * @return RelationInterface
     */
    public function setStatus($status);

    /**
     * Set update time
     *
     * @param  string $time
     * @return RelationInterface
     */
    public function setUpdatedAt($time);
}
