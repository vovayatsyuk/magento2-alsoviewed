<?php

namespace Vovayatsyuk\Alsoviewed\Block\Widget;

class ListProduct extends \Magento\Framework\View\Element\Template
{
    protected function _beforeToHtml()
    {
        // Move widget options into ListProduct block
        $data = $this->getData();
        $data['template'] = $this->getTemplate();
        unset($data['type']);
        unset($data['module_name']);

        $list = $this->addChild(
            'products',
            'Vovayatsyuk\Alsoviewed\Block\ListProduct',
            $data
        );

        // Populate `this` block data
        $this->setBlockTitle($this->getTitle());
        $this->setBlockCss($this->getCssClass());
        if ($this->getNoWrapper()) {
            $this->setTemplate('Magento_Theme::html/container.phtml');
        } else {
            $this->setTemplate('Magento_Theme::html/block.phtml');
        }

        return parent::_beforeToHtml();
    }
}
