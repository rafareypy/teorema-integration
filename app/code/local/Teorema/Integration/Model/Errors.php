<?php
class Teorema_Integration_Model_Errors extends Mage_Core_Model_Abstract {

    public function _construct() {
        $this->_init('teorema_integration/errors');
    }

    public function log(){
      Mage::log("log desde cron do Magento");
    }
}
