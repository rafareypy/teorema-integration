<?php
class Teorema_Integration_Model_Order_Observer
{

    public function synchronize_order($observer){
      $order = $observer->getEvent()->getOrder();
	  //Mage::log($order->getData(), null, 'teorema_integration.log');
	  $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
      //Mage::log($customer->getData(), null, 'teorema_integration.log');
      $customer_address_id = $customer->getDefaultShipping(); //Endereço de entrega
	  $address = Mage::getModel('customer/address')->load($customer_address_id);
	  //Mage::log($address->getData(), null, 'teorema_integration.log');
	  $teorema_service_customer = Mage::getModel('teorema_integration/service_customer');
	  //Mage::log($customer->getTeoremaCode(), null, 'teorema_integration.log');
	  //$teorema_service_customer->getCustomerToTeorema()
    }
}
?>
