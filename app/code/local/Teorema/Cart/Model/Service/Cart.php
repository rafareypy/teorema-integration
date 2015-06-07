<?php
class Teorema_Cart_Model_Service_Cart extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

 /**
 * Função que busca carrinhos abandonados na loja
 *
 */
  public function searchAbandonedCarts(){

  	$orderCollection = Mage::getModel('sales/order')
						->getCollection()
						->addAttributeToSelect('*');

    $teoremaCartCollection = Mage::getModel("teorema_cart/cart")->getCollection();

    $teoremaCartsArray = array();

    foreach ($teoremaCartCollection as $key => $cart) {    	
    	array_push($teoremaCartsArray, $cart->getCartId());    	
    }

	foreach ($orderCollection as $key => $order) 
	{
		
		$key = array_search($order->getEntityId(), $teoremaCartsArray); 
		
		if(gettype($key) == 'boolean' && $key == false){
			echo " <br/>não encontramos valores para " . $order->getEntityId();
			$teoremaCart = Mage::getModel("teorema_cart/cart");
			$teoremaCart->setCustomerId($order->getCustomerId());
			$teoremaCart->setEmail($order->getCustomerEmail());
			$teoremaCart->setStatus('active');
			$teoremaCart->setCartId($order->getEntityId());
			$teoremaCart->setIncrementId($order->getIncrementId());		
			$teoremaCart->setGrandTotal($order->getGrandTotal());
			$teoremaCart->setProductsId($order->getIncrementId());


			$this->saveTeoremaCart($teoremaCart);

		}

	}

  }

  /**
  * Função responsavel por salvar os carrinhos abandonados (Teorema Cart)
  * @param $teoremaCart Model teorema cart
  * @return $teoremaCart Model teorema cart
  */
  public function saveTeoremaCart($teoremaCart)
  {
  	$teoremaCartResult = null ;
	
	try{
		$teoremaCart->save();			
		$teoremaCartResult = $teoremaCart ;
	}catch(Exception $e){
			
	}

	return $teoremaCartResult ;
  }

  /**
  * Função responsavel por enviar emails de todos os carrinhos abandonados 
  * @param $teoremaCart Model teorema cart
  * @return $teoremaCart Model teorema cart
  */
  public function sendEmailToAbandonedCarts(){


	$teoremaCartCollection = Mage::getModel("teorema_cart/cart")->getCollection();

	foreach ($teoremaCartCollection as $key => $cart) {		
		
		if($cart->getStatus() == 'active'){			


			$order = $this->getOrder($cart->getCartId());
			
			if($order && !is_null($order)){

				if($order->getStatus() == 'pending'){
					$this->sendEmail($order);					
					$cart->setNumberOfRetries(($cart->getNumberOfRetries() + 1));
					if($cart->getNumberOfRetries() > 2)
						$cart->setStatus('closed');	

				}else{
					$cart->setStatus('sold');
				}
				$this->saveTeoremaCart($cart);
			}

		}

	}
	

  }

  /**
  * Função que retorna Order Magento desde o Id
  *
  */
  public function getOrder($id){

  	$order = null ;

  	if($id){
  		$order = Mage::getModel('sales/order')->load($id);
  	}

  	return $order ;

  }

  public function sendEmail($order){

  }

}