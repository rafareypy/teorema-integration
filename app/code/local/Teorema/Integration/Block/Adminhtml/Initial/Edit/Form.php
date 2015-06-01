<?php
class Teorema_Integration_Block_Adminhtml_Errors_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
    {
      echo "prepara form to Teorema_Integration_Block_Adminhtml_Errors_Edit_Form ";
      die();
        $form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
			'method' => 'post',
            'enctype' => 'multipart/form-data'
		));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
