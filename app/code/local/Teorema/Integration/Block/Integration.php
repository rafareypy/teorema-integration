<?php
class Teorema_Integration_Block_Integration extends Mage_Core_Block_Template
{
    public function getCollection(){

        return Mage::getModel('teorema_integration/integration')->getCollection()
            ->addFieldToFilter('reviewed', 1)
            ->setOrder('ranking', 'ASC')
            ->load();
    }

    public function getAddressCustomer($customerObj){
        foreach ($customerObj->getAddresses() as $address){
            $customerAddress = $address->toArray();
            break;
        }

        return array('city' => $customerAddress['city'], 'state' => $customerAddress['region']);
    }
}
