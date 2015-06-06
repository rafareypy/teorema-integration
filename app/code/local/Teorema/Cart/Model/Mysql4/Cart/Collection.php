<?php
class Teorema_Cart_Model_Mysql4_Cart_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('teorema_cart/cart');
    }
}
