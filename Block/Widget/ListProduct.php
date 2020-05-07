<?php

namespace Vovayatsyuk\Alsoviewed\Block\Widget;

use Magento\Framework\View\Element\Template;

class ListProduct extends Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * This method allows to disable bock in product tabs
     *
     * @return boolean
     */
    public function getEnabled()
    {
        $enabled = $this->getData('enabled');
        if (null !== $enabled) {
            return (bool) $enabled;
        }
        return true;
    }

    public function getJsonConfig()
    {
        $data = $this->getData();
        $data['template'] = $this->getTemplate();

        $unset = [
            'type',
            'title',
            'module_name',
            'block_css',
            'block_title',
        ];
        foreach ($unset as $key) {
            unset($data[$key]);
        }

        $productId = null;
        $product = $this->registry->registry('current_product');
        if ($product) {
            $productId = $product->getId();
        }

        return $this->jsonEncoder->encode([
            'url' => $this->getUrl('alsoviewed/product/listAjax', [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $productId,
            ]),
            'blockData' => $data,
        ]);
    }

    /**
     * This method allows to configure block in product tabs
     *
     * @param string $path
     */
    public function addDataFromConfig($path)
    {
        $this->addData(
            $this->_scopeConfig->getValue(
                $path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
        return $this;
    }

    protected function _beforeToHtml()
    {
        if (!$this->getBlockTitle()) {
            $this->setBlockTitle($this->getTitle());
        }

        // ability to set custom block wrapper template
        if (!$template = $this->getBlockTemplate()) {
            $template = 'Vovayatsyuk_Alsoviewed::block.phtml';
        }
        $this->setTemplate($template);

        return parent::_beforeToHtml();
    }
}
