<?php
class Teorema_Integration_Block_Adminhtml_Errors_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	   public function __construct()
    {

      echo "Construct Teorema_Integration_Block_Adminhtml_Errors_Edit ";
      die();

        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'teorema_integration';
        $this->_controller = 'adminhtml_errors';

        $this->_updateButton('save', 'label', 'Salvar registro');
        $this->_updateButton('delete', 'label', 'Excluir');
    }

    public function getHeaderText()
    {
        if( Mage::registry('teorema_integration_data') && Mage::registry('teorema_integration_data')->getId() ) {
            return 'Editar registro "' . $this->htmlEscape(Mage::registry('teorema_integration_data')->getName()) . '"';
        } else {
            return 'Adicionar novo registro';
        }
    }
}
