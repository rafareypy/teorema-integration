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

    *
  */

  /*Função que atualiza o estoque no Magento */
  public function updateStock(){

     $productService = Mage::getModel('teorema_integration/service_product') ;

     $serviceBalance = Mage::getModel('teorema_integration/service_balance');

     $limit_attempts = Mage::getStoreConfig("teorema/teorema_integration/limit_attempts");

     if(is_null($limit_attempts))
        $limit_attempts = 3 ;

     $limit = Mage::getStoreConfig("teorema/teorema_integration/indexer_limit");

     if(is_null($limit))
        $limit = 80 ;

     /*TODO verificar que a busca seja por todos pendentes ou processando*/
     $collection =  Mage::getModel('teorema_integration/tableschanged')->getCollection();
     $collection->addFieldToFilter('status', 'pending')->setPageSize($limit);
     $collection->addFieldToFilter('type', 'stock')->load();

     foreach ($collection as $key => $tableschanged)
     {

       $sku = $tableschanged->getIdValue() ;

       #soma a quantidade de tentativas em atribuir o valor do estoque a este produto..
       $tableschanged = $this->sumTableschanged($tableschanged);

       if(!is_null($tableschanged) and $tableschanged->getNumberOfRetries() < $limit_attempts and !is_null($sku)){

         #obtemos a quantidade em estoque do produto
         $availableBalance = $serviceBalance->availableBalance($sku);

         $qty = 0 ;
         if($availableBalance){
           $qty = $availableBalance->ESTOQUEQUANTIDADEDISPONIVEL;
         }

         $productMagento = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

         $productMagento = Mage::getModel('catalog/product')->load($productMagento->getId());

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
         Mage::log(" Teorema_Integration_Model_Service_Stock :
                        Error in update product  " . $e->getMessage(),
                         null, "update_stock.log");
       }

     }

  }

  #soma a quantidade de tentativas em atribuir o valor do estoque a este produto..
  public function sumTableschanged($tableschanged){

    if(is_null($tableschanged))
      return null ;

    #Verifica limite de tentativas para atualizar..
    $tableschanged->setNumberOfRetries($tableschanged->getNumberOfRetries() + 1);

    if($tableschanged->getNumberOfRetries() < ($limit_attempts + 1)){
      $tableschanged->setStatus('processing');
      $this->updateTablesChanged($tableschanged);
    }

    return $tableschanged ;

  }

  public function updateTablesChanged($tableschanged){

    try{
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
