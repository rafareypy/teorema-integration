<?php
class Teorema_Integration_Block_Adminhtml_Modifiedtables extends Mage_Adminhtml_Block_Widget_Grid_Container
{

		public function __construct()
    {
        $this->_controller = 'adminhtml_modifiedtables';
        $this->_blockGroup = 'teorema_integration';
        $this->_headerText = 'Tabelas Modificadas';
        $this->_addButtonLabel = 'Adiciona novo registro';
        parent::__construct();
    }
}
