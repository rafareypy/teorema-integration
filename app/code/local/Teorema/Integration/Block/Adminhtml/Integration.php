<?php
class Teorema_Integration_Block_Adminhtml_Integration extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct(){
        $this->_controller = 'adminhtml_integration';
        $this->_blockGroup = 'teorema_integration';
        $this->_headerText = 'Tabelas Alteradas';
        $this->_addButtonLabel = null;
        parent::__construct();
    }
}
