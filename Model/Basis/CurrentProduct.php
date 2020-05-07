<?php

namespace Vovayatsyuk\Alsoviewed\Model\Basis;

class CurrentProduct implements BasisInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface $request
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

        $id = $this->request->getParam('id');
        if ($id) {
            $result[] = $id;
        }

        return $result;
    }
}
