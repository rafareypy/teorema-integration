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
  public function updateStock($arrayStatus){

      if(is_null($arrayStatus))
        $arrayStatus = array('pending');

     $productService = Mage::getModel('teorema_integration/service_product') ;

     $serviceBalance = Mage::getModel('teorema_integration/service_balance');

     /*TODO verificar que a busca seja por todos pendentes ou processando*/
     $collection =  Mage::getModel('teorema_integration/tableschanged')->getCollection();
     $collection->addFieldToFilter('status', $arrayStatus)->setPageSize($this->indexer_limit);
     $collection->addFieldToFilter('type', 'stock')->load();


     foreach ($collection as $key => $tableschanged)
     {
       //Otendo o sku do produto que foi alterado
       $sku = $tableschanged->getIdValue() ;

       #soma a quantidade de tentativas em atribuir o valor do estoque a este produto..
       $tableschanged = $this->sumTableschanged($tableschanged);

       if(!is_null($tableschanged) and $tableschanged->getNumberOfRetries() < $this->limit_attempts and !is_null($sku)){

         #obtemos a quantidade em estoque do produto
         $availableBalance = $serviceBalance->availableBalance($sku);

         $qty = 0 ;
         if($availableBalance){
           $qty = $availableBalance->ESTOQUEQUANTIDADEDISPONIVEL;
         }

         #Obtendo o produto Magento desde o sku..
         $productMagento = $this->getProductMagento($sku);

         $productMagento->setStockData(array(
                 'qty' => $qty,
                 'manage_stock' => 1,
                 'is_in_stock' => ($qty >= 0) ? true : false
               ));

       }


       try{
         $productMagento->save();
         $tableschanged->setStatus('processed');
         $this->updateTablesChanged($tableschanged);
       }catch(Exception $e){

         $message = " Teorema_Integration_Model_Service_Stock :
                        Error in update product  " . $e->getMessage() ;

         $this->saveErrosLog($message);


         Mage::log($message, null, "update_stock.log");
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
      }else{
        $product = Mage::getModel('catalog/product')->load($product->getId());
      }

    }

    return $product ;

  }

  #soma a quantidade de tentativas em atribuir o valor do estoque a este produto..
  public function sumTableschanged($tableschanged){

    if(is_null($tableschanged))
      return null ;

    #Verifica limite de tentativas para atualizar..
    $tableschanged->setNumberOfRetries($tableschanged->getNumberOfRetries() + 1);


    if($tableschanged->getNumberOfRetries() < ($this->limit_attempts + 1)){
      $tableschanged->setStatus('processing');
      $this->updateTablesChanged($tableschanged);
    }

    return $tableschanged ;

  }

  public function updateTablesChanged($tableschanged){

    try{

      echo "<br/>Atualizando status de tabela alterada " . $tableschanged->getStatus() . "<br/>";

      $tableschanged->save();
    }catch(Exception $e){
      $tableschanged = null ;

       Mage::log(" Teorema_Integration_Model_Service_Stock : Error in update tableschanged  " .
                       $e->getMessage(),
                       null, "update_stock.log");
    }

    return $tableschanged ;
  }

}
