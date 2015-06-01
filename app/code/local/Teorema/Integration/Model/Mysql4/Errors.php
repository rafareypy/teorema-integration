<?php
class Teorema_Integration_Model_Mysql4_Errors extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // getTable
        $this->_init('teorema_integration/teorema_integration_errors', 'id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if ($object->isObjectNew()) {
            $object->created_at = date('Y-m-d H:i:s');
        }

        $object->updated_at = date('Y-m-d H:i:s');

        return parent::_beforeSave($object);
    }
}
