<?php
class Teorema_Integration_Block_Integration extends Mage_Core_Block_Template
{
    public function getCollection(){

        return Mage::getModel('teorema_integration/integration')->getCollection()
            ->load();
    }

  
}
