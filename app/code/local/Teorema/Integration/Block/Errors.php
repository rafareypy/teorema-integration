<?php
class Teorema_Integration_Block_Adminhtml_Errors extends Mage_Adminhtml_Block_Widget_Grid_Container
{

		public function __construct()
    {

        $this->_controller = 'adminhtml_errors';
        $this->_blockGroup = 'teorema_integration';
        $this->_headerText = 'Erros na Integração';
        $this->_addButtonLabel = 'Adiciona novo registro';
        parent::__construct();
    }
}
