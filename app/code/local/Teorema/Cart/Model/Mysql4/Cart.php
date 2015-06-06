<?php
class Teorema_Cart_Model_Mysql4_Cart extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('teorema_cart/teorema_cart', 'id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if ($object->isObjectNew()) {
            $object->created_at = date('Y-m-d H:i:s');
        }

        $object->updated_at = date('Y-m-d H:i:s');

        return parent::_beforeSave($object);
    }
}
