<?php

class Teorema_Integration_Block_Adminhtml_Integration_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct() {
        parent::__construct();
        $this->setId('integration_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Integarção');
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label'     => 'Edição',
            'title'     => 'Edição',
            'content'   => $this->getLayout()->createBlock('teorema_integration/adminhtml_integration_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
