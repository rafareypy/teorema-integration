<?php
class Teorema_Integration_Model_Service_Stock extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }


  /*
     *O cron estara verificando, de acordo com as configurações do usuario as tabeals alteradas
      logo verificar se nestes dados esta presente o estoque e se
      tiver adicionar valores em tableschaged para que o indexador verifique posteriormente

  */

  /*
    Função que atualiza o estoque no Magento com relação a tabelas alteradas
    que ja foram carregadas em outr processo
    Teorema_Integration_Model_Service_TablesChangedTeorema->updateTablesChangedTeorema
  */
  public function updateStock($arrayStatus, $idTableschanged){
      if(is_null($arrayStatus))
        $arrayStatus = array('pending');

     $tableschangedTeoremaService = Mage::getModel('teorema_integration/service_tableschangedteorema');

     $productService = Mage::getModel('teorema_integration/service_product') ;

     $serviceBalance = Mage::getModel('teorema_integration/service_balance');

     /*TODO verificar que a busca seja por todos pendentes ou processando*/
     $collection =  Mage::getModel('teorema_integration/tableschanged')->getCollection();
     $collection->addFieldToFilter('status', $arrayStatus)->setPageSize($this->indexer_limit);

     if(!is_null($idTableschanged))
      $collection->addFieldToFilter('id', $idTableschanged);

     $collection->addFieldToFilter('type', 'stock')->load();

     foreach ($collection as $key => $tableschanged)
     {
       //Otendo o sku do produto que foi alterado
       $sku = $tableschanged->getIdValue() ;

       #soma a quantidade de tentativas em atribuir o valor do estoque a este produto..
       $tableschanged = $tableschangedTeoremaService->sumTableschanged($tableschanged);

       if(!is_null($tableschanged) and $tableschanged->getNumberOfRetries() < $this->limit_attempts and !is_null($sku))
       {

         #obtemos a quantidade em estoque do produto
         $availableBalance = $serviceBalance->availableBalance($sku);

         $qty = 0 ;
         if($availableBalance['success'] &&  !empty($availableBalance['data']) ){
           $qty = $availableBalance['data']->ESTOQUEQUANTIDADEDISPONIVEL;
         }elseif(!$availableBalance['success']){
           $message = " Observação não foi possivel consultar o estoque do produto W.S. :" .$e->getMessage() ;
           Mage::getSingleton('adminhtml/session')->addWarning($message);
           $this->saveErrosLog($message, '0', 'stock', $tableschanged->getLastIdUpdated() , $tableschanged->getId());
         }

         #Obtendo o produto Magento desde o sku..
         $productMagento = $this->getProductMagento($sku);

         $productMagento->setStockData(array(
                 'qty' => $qty,
                 'manage_stock' => 1,
                 'is_in_stock' => ($qty >= 0) ? true : false
               ));

         try{

           $productMagento->save();
           $tableschanged->setStatus('processed');
           $tableschangedTeoremaService->updateTablesChanged($tableschanged);
         }catch(Exception $e){

           if(!is_null($productMagento) && !is_null($productMagento->getId()) )
                $id = $productMagento->getId() ;

           $message = " Teorema_Integration_Model_Service_Stock :
                          Error in update product Id = $id " . $e->getMessage() ;
           Mage::getSingleton('core/session')->addError($message);                 

           $this->saveErrosLog($message, '0', 'stock', $tableschanged->getLastIdUpdated() , $tableschanged->getId());
         }

       }
       #Em cado que a quantidade de tentativas supere o $this->limit_attempts então devemos trocar o status para Error
       else if(!is_null($tableschanged) and $tableschanged->getNumberOfRetries() >= $this->limit_attempts and !is_null($sku))
       {
         $tableschanged->setStatus('error');
         $tableschangedTeoremaService->updateTablesChanged($tableschanged);
       }

     }

  }

  /*
    Função responsavel por buscar o produto dentro do Magento, caso o mesmo não exista sera criado..
  */
  public function getProductMagento($sku){

    $product = null ;

    if(!is_null($sku)){
      $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

      $serviceProduct = Mage::getModel('teorema_integration/service_product');

      /*TODO refactor*/
      if(!$product or is_null($product)){
        $product = $serviceProduct->createProductMagento($sku);
        $product = Mage::getModel('catalog/product')->load($product->getId());
      }else{
        $product = Mage::getModel('catalog/product')->load($product->getId());
      }

    }
    return $product ;
  }


}
