<?php
class Teorema_Integration_Model_Cart_Observer extends Mage_Core_Model_Abstract {


  public function checkingStockProduct($observer){

    $product = $observer->getProduct() ;

    /*TODO verificar */
    $qty = 0;
    $result = Mage::getModel('teorema_integration/service_balance')
                                  ->availableBalance($product->getSku());

    if($result['success'] && !empty($result['data']) )
      $availableBalance = $result['data'];


    if($availableBalance){
      $qty = $availableBalance->ESTOQUEQUANTIDADEDISPONIVEL;
    }

    if($qty <= 0){

      $product->setStockData(array(
              'qty' => $qty,
              'manage_stock' => 1,
              'is_in_stock' => false
            ));

      $product->save();

    }


  }

}
