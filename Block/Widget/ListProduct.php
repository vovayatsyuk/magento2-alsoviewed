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

        // ability to use different title (tab) and block title (content)
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
