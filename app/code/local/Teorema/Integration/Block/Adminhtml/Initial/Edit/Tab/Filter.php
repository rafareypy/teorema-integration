<?php
class Teorema_Integration_Block_Adminhtml_Errors_Edit_Tab_Filter extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
