<?php
class Teorema_Integration_Model_Mysql4_Initial_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('teorema_integration/initial');
    }
}
