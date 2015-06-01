<?php
class Teorema_Integration_Block_Adminhtml_Errors_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('teorema_integration_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Informações gerais');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => 'Informações gerais',
            'title'     => 'Informações gerais',
            'content'   => $this->getLayout()->createBlock('teorema_integration/adminhtml_errors_edit_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}
