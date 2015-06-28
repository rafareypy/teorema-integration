<?php
class Teorema_Cart_Model_Service_Email extends Teorema_Cart_Model_Service
{

  function __construct(){
      parent::__construct();
  }

 
  public function sendEmail($teoremaCart)
  {

    $customer = Mage::getModel('customer/customer')->load($teoremaCart->getCustomerId()); // insert customer ID    

    $templateId = Mage::getStoreConfig("teorema/teorema_cart/template");

    // Set sender information
    $senderName = Mage::getStoreConfig("teorema/teorema_cart/sender_name");
    $senderEmail = Mage::getStoreConfig("teorema/teorema_cart/sender_email");

    $sender = array('name' => $senderName,
    'email'=> $senderEmail);

    // Set recepient information
    $recepientEmail = $customer->getEmail() ;
    $recepientPassword = $customer->getName();


    $cuponCode = $this->getCuponCodeToDiff($teoremaCart->getNumberOfRetries());

    // Get Store ID
    $store = Mage::app()->getStore()->getId();

    // Set variables that can be used in email template
    $vars = array('customer'=> $recepientEmail,
    'cust'=> $recepientPassword, 'name' => $customer->getName(),
    'cupon' => $cuponCode
    );

    $translate = Mage::getSingleton('core/translate');

    try{
      Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $recepientEmail, $recepientPassword, $vars, $storeId);
    }catch(Exception $e){
      print_r($e);
    }    

  }

  public function getCuponCodeToDiff($diff){

  $cuponCode = Mage::getStoreConfig("teorema/teorema_cart/promotion_code");

	switch ($diff) {
	    case 1:
	        $cuponCode = Mage::getStoreConfig("teorema/teorema_cart/promotion_code_second");
	        break;
	    case 2:
	        $cuponCode = Mage::getStoreConfig("teorema/teorema_cart/promotion_code_third");
	        break;
	}

	return $cuponCode ;

  }

}