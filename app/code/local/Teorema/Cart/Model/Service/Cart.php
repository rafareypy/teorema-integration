<?php
class Teorema_Cart_Model_Service_Cart extends Teorema_Cart_Model_Service
{

  function __construct(){
      parent::__construct();
  }

 /**
 * Função que busca carrinhos abandonados na loja
 *
 */
  public function searchAbandonedCarts(){

    $collectionQuote = Mage::getResourceModel('reports/quote_collection');
    $collectionQuote -> prepareForAbandonedReport($storeIds, $filter = null);

    $teoremaCartCollection = Mage::getModel("teorema_cart/cart")->getCollection();

    $teoremaCartsArray = array();

    foreach ($teoremaCartCollection as $key => $cart) {    	
    	array_push($teoremaCartsArray, $cart->getCustomerId());    	
    }
	
	foreach ($collectionQuote as $key => $quote) 
	{
		
		$key = array_search($quote->getCustomerId(), $teoremaCartsArray); 
		
		if(gettype($key) == 'boolean' && $key == false){			
			$teoremaCart = Mage::getModel("teorema_cart/cart");
			$teoremaCart->setCustomerId($quote->getCustomerId());
			$teoremaCart->setEmail($quote->getCustomerEmail());
			$teoremaCart->setStatus('active');
			$teoremaCart->setCartId(1);
			$teoremaCart->setIncrementId("001");		
			$teoremaCart->setGrandTotal($quote->getGrandTotal());
			$teoremaCart->setProductsId($quote->getIncrementId());			
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
	$collectionQuote = Mage::getResourceModel('reports/quote_collection');			
	$collectionQuote -> prepareForAbandonedReport($storeIds, $filter = null);

	/*TODO filtar por buca caririhnos 'abertos'*/
	$teoremaCartCollection = Mage::getModel("teorema_cart/cart")->getCollection();
	$teoremaCartCollection->addFieldToFilter('status', 'active');

    $teoremaCartsArray = array();
    foreach ($teoremaCartCollection as $key => $cart) {
    	array_push($teoremaCartsArray, $cart->getCustomerId());    	
    }
	
	foreach ($collectionQuote as $key => $quote) {
		$key = array_search($quote->getCustomerId(), $teoremaCartsArray); 
	
		/*TODO refatorar*/		
		if(gettype($key) != 'boolean'){

			$teoremaCart = Mage::getModel("teorema_cart/cart")->getCollection()
									->addFieldToFilter('customer_id', $quote->getCustomerId())
									->getFirstItem();

			if(!is_null($teoremaCart) && isset($teoremaCart['id'])){
				$teoremaCart->setNumberOfRetries(($teoremaCart->getNumberOfRetries() + 1));
				if($teoremaCart->getNumberOfRetries() > 2){
					$teoremaCart->setStatus('closed');
					$this->sendEmail($teoremaCart);	
				}else{
					$this->sendEmail($teoremaCart);
				}

				$this->saveTeoremaCart($teoremaCart);						
			}
		}			
	}
	
  }

  /**
  * Função que retorna Order Magento desde o Id
  *
  */
  public function getQuote($id){

  	$order = null ;

  	if($id){
  		$order = Mage::getModel('sales/quote')->load($id);
  	}

  	return $order ;

  }

  public function sendEmail($teoremaCart){

  	if(!is_null($teoremaCart)){
		$emailService = Mage::getModel("teorema_cart/service_email");
		$emailService->sendEmail($teoremaCart);  		
  	}

  }

}