<?php

class Teorema_Integration_Block_Adminhtml_Integration_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('integration_form', array('legend'=>'Informações do Item'));

        $fieldset->addField('name', 'text', array(
            'label'     => 'Nome',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));




        if ( Mage::getSingleton('adminhtml/session')->getIntegrationData() ) {
                $form->setValues(Mage::getSingleton('adminhtml/session')->getIntegrationData());
                Mage::getSingleton('adminhtml/session')->setIntegrationData(null);
            } elseif ( Mage::registry('integration_data') ) {
                $form->setValues(Mage::registry('integration_data')->getData());
            }
            return parent::_prepareForm();
        }
}
