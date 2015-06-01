<?php
class Teorema_Integration_Block_Adminhtml_Errors_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $this->setForm($form);

        $fieldset = $form->addFieldset('teorema_integration_form', array('legend'=> 'Informações gerais'));



        return parent::_prepareForm();
    }
}
