<?php
class Teorema_Cart_Model_Service_Email extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

 
  public function sendEmail($order, $teoremaCart)
  {

    if($order->getStatus() != 'pending')
      return ;
    

    $date1 = new DateTime($order['created_at']);
    $date2 = new DateTime();

    $diff = $date2->diff( $date1 );

    $cuponCode = $this->getCuponCodeToDiff($diff);

    #envia email com cupom TODO 

    //$customerEmail = $order['customer_email'];
    $customerEmail = 'rreynoud@gmail.com';
    $customerFirstName = $order['customer_firstname'] ;

    $email = Mage::getModel('core/email_template');
    $email->setSubject('Lembrete de Carrinho ');

    $email->sendTransactional(
      'email_abandonedcart',
      'general',
      $customerEmail,      
      $customerFirstName,
      'products',
      $order['store_id']
    );


  }

  public function getCuponCodeToDiff($diff){

  	$cuponCode = Mage::getStoreConfig("teorema/teorema_cart/promotion_code_third");

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