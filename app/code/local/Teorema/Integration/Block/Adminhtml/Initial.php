<?php
class Teorema_Integration_Block_Adminhtml_Initial extends Mage_Adminhtml_Block_Widget_Grid_Container
{

		public function __construct()
    {

        $this->_controller = 'adminhtml_initial';
        $this->_blockGroup = 'teorema_integration';
        $this->_headerText = 'Skus (ITEMREDUZIDO) Carga Inicial:';
        $this->_addButtonLabel = 'Adiciona novo registro';
        parent::__construct();
    }
}
