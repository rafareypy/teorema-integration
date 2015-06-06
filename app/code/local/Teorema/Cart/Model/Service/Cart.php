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

	foreach ($orderCollection as $key => $order) 
	{
		echo  "<br/> Carrinho abandonados "	;

		$teoremaCart = Mage::getModel("teorema_cart/cart");
		$teoremaCart->setCustomerId($order->getCustomerId());
		$teoremaCart->setEmail($order->getCustomerEmail());
		$teoremaCart->setStatus('active');
		$teoremaCart->setCartId($order->getEntityId());
		$teoremaCart->setIncrementId($order->getIncrementId());
		$teoremaCart->setIncrementId($order->getIncrementId());
		$teoremaCart->setTotalInvoiced($order->getIncrementId());
		$teoremaCart->setProductsId($order->getIncrementId());

		try{
			$teoremaCart->save();
			die("Carrhino salvo com sucesso.");
		}catch(Exception $e){
			die("Erro ao tentar salvar o valor carrinho <br/> " . $e->getMessage() );
		}
		

		var_dump($order);
	}



	die();


  }

}