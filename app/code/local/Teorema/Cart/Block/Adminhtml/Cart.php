<?php
class Teorema_Cart_Block_Adminhtml_Cart extends Mage_Adminhtml_Block_Widget_Grid_Container
{

		public function __construct()
    {
        $this->_controller = 'adminhtml_cart';
        $this->_blockGroup = 'teorema_cart';
        $this->_headerText = 'Carrinhhos abandonados';
        $this->_addButtonLabel = 'Adiciona novo registro';
        parent::__construct();
    }
}
