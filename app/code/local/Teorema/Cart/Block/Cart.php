<?php
class Teorema_Cart_Block_Adminhtml_Cart extends Mage_Adminhtml_Block_Widget_Grid_Container
{

		public function __construct()
    {

			echo "construct to Teorema_Cart_Block_Adminhtml_Cart ";
			die();

        $this->_controller = 'adminhtml_cart';
        $this->_blockGroup = 'teorema_cart';
        $this->_headerText = 'Carrinhos abandonados';
        $this->_addButtonLabel = 'Adiciona novo registro';
        parent::__construct();
    }
}
