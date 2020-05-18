<?php

namespace Vovayatsyuk\Alsoviewed\Model\Basis;

class RecentlyViewed implements BasisInterface
{
    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $request;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
    }

    public function getIds()
    {
        $result = [];

        $ids = $this->request->getParam('recently_viewed_ids');
        if ($ids) {
            if (!is_array($ids)) {
                $ids =  explode(',', $ids);
            }
            $result = $ids;
        }

        return $result;
    }
}
