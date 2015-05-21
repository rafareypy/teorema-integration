<?php

class Teorema_Integration_Block_Adminhtml_Integration_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'teorema_integration';
        $this->_controller = 'adminhtml_integration';

        $this->_updateButton('save', 'label', 'Salvar');
        $this->_updateButton('delete', 'label', 'Deletar');
    }

    public function getHeaderText() {
        if( Mage::registry('integration_data') && Mage::registry('integration_data')->getId() ) {
            return Mage::helper('teorema_integration')->__("Editar Item %s", $this->htmlEscape(Mage::registry('integration_data')->getTitle()));
        } else {
            return Mage::helper('teorema_integration')->__('Adicionar Item');
        }
    }
}
