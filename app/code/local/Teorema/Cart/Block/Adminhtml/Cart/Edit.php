<?php
class Teorema_Cart_Block_Adminhtml_Cart_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	   public function __construct()
    {

      echo "Construct Teorema_Cart_Block_Adminhtml_Cart_Edit ";
      die();
      
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'teorema_cart';
        $this->_controller = 'adminhtml_cart';

        $this->_updateButton('save', 'label', 'Salvar registro');
        $this->_updateButton('delete', 'label', 'Excluir');
    }

    public function getHeaderText()
    {
        if( Mage::registry('teorema_cart_data') && Mage::registry('teorema_cart_data')->getId() ) {
            return 'Editar registro "' . $this->htmlEscape(Mage::registry('teorema_cart_data')->getName()) . '"';
        } else {
            return 'Adicionar novo registro';
        }
    }
}
