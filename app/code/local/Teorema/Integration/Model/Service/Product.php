<?php
class Teorema_Integration_Model_Service_Product extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }



# kit, é um agrupamento de produtos sem atributo
# agrupado é usando 1 atributo
# e configurável é usando 1 ou mais atributos

  /**
	 * Função Retorna todos os produtos desde o Web Service Teorema
	 * @return JSON products
	 */
  public function getProductsToTeorema(){

    //Senha sera adicionaod depois on metodo connectionGet
    $params = array(
      'USUARIO' 	=> $this->user,
      'METODO' 	  => 'ecomItemTodosConsulta',
      'SENHA'     => "",
      'SISTEMA' 	=> 'ecommerce',
      'SENHA_REF' => $this->password
    );

    return $this->connectionGet($params);

  }


  /**
	 * Função que busca um determinado produto no Web Service Teorema
   * @param $sku = ITEMREDUZIDO
	 * @return JSON product Model1Product
	 */
  public function getProductJsonToTeorema($sku){

    $params = array(
      'USUARIO'   => $this->user,
      'METODO'    => 'ecomItemConsulta',
      'SENHA_REF' => $this->password,
      'SENHA'     => $this->password_md5,
      'EMPRESACODIGO' => '0001',
      'ITEMREDUZIDO' => $sku
    );

    return $this->connectionGet($params);

  }

  /**
	 * Função Traz todos os codigos 'ITEMREDUZIDO' do webservice Teorema
	 * @return JSON products [{"ITEMREDUZIDO":"006733"},{"ITEMREDUZIDO":"006735"}]
	 */
  public function getAllProductsToTeorema(){

    $params = array(
      'USUARIO'     => $this->user,
      'METODO'      => 'ecomItemTodosConsulta',
      'SENHA_REF'   => $this->password,
      'SENHA'       => $this->password_md5,
      'EMPRESACODIGO' => '0001',
      'ITEMSISTEMA' => 'T'
    );

    return $this->connectionGet($params);

  }

  /**
	 * Web service (ecomItemTodosGrade) Teorema que retorna toda a grade de produtos
	 * @return JSON products
	 */
  public function getAllGroupedProductToTeorema(){
    $params = array(
			'USUARIO'  => $this->user,
			'METODO'   => 'ecomItemTodosGrade',
			'SENHA_REF'=> $this->password,
			'SENHA'    => $this->password_md5,
			'EMPRESACODIGO' => '0001'
		);

    return  $this->connectionGet($params);

	}


  public function getGroupedProductToTeorema($sku){
    $params = array(
    			'USUARIO' => $this->user,
    			'METODO' => 'ecomItemGrade',
    			'SENHA_REF' => $this->senha,
    			'SENHA' => $this->senha_md5 ,
    			'EMPRESACODIGO' => '0001',
    			'ITEMREDUZIDO' => $sku
    		);

    return $this->connectionGet($params);

  }


  /**
	 * Cria produto Teorema no Magento  desde o sku (ITEMREDUZIDO)
   * Metodo ira buscar no w.s. teorema o produto
   * @param sku
	 * @return product Magento
	 */
  public function createProductMagento($sku)
  {
    #Verifica se o produto existe
    $product = Mage::getModel('catalog/product')
                        ->loadByAttribute('sku', $sku);

    if($product){
      echo "<br>\n Product  existis $sku \n<br/> ";
    }else{
        echo "<br/>\nCreating product\n<br/>";
        #Se o produto não existe, então sera buscado o Json do mesmo no webService da Teorema..
        $result = $this->getProductJsonToTeorema($sku);

        if($result['success'] && !empty($result['data']) ){
            $productTeorema = $result['data'];
            #Obtendo um novo produto Magento
            $product     = $this->getNewProductMagentoToJson($productTeorema);

            $product = $this->saveProduct($product);
        }else{
          Mage::getSingleton(‘core/session’)->addError('Erro ao tentar criar um produto Magento <br/>' . $result['message'] );
          $this->saveErrosLog($result['message'], '0', 'product', 0, 0) ;
        }

    }

    return $product ;
  }

  /**
	 * Função responsavel por criar produto Magento
   * Recebe como parametro o objeto json Teorema webService
   * @param $productJson produto teorema JSON
   * @param $productMagentoUpdate recebendo este parametro a função ira atualizar o produto,
   *        com parametros do $productJson
	 * @return product Magento
	 */
  public function getNewProductMagentoToJson($productJson, $productMagentoUpdate){

    $category = array(1, 3);

    $productMagento = Mage::getModel('catalog/product');

    if(!is_null($productMagentoUpdate) && !is_null($productMagentoUpdate->getId())  )
      $productMagento = $productMagentoUpdate ;

    #Falta:
    #verifica grupos e subgrupos 'possivelmente é a categoria no magento'
    #Verificar se esta ok categorias..


    $productMagento->setStoreIDs(array(1));
    $productMagento->setWebsiteIDs(array(1));
    $productMagento->setAttributeSetId(4);
    $productMagento->setTypeId('simple');
    $productMagento->setPageLayout('one_column');
    $productMagento->setTaxClassId(0);
    $productMagento->setVisibility(4);

    $name = utf8_encode(utf8_decode($productJson->ITEMDESCRICAO2));

    $productMagento->setName($name);

    $productMagento->setDescription(utf8_encode(utf8_decode($productJson->ITEMDESCRICAO)));

    if(isset($productJson->ITEMFICHATECNICA)){
        $productMagento->setDescription(utf8_encode(utf8_decode($productJson->ITEMFICHATECNICA)));
    }


    $productMagento->setShortDescription(utf8_encode(utf8_decode($productJson->ITEMDESCRICAO)));
    $productMagento->setSku($productJson->ITEMREDUZIDO);

    $weight = 0;

    if(isset($productJson->ITEMPESOLIQUIDO)){
      $weight = $productJson->ITEMPESOLIQUIDO;
    }

    $productMagento->setWeight($weight);

    $price = 0;
    if(isset($productJson->ITENSPLANOPRECOMOVIMENTO[0]) && isset($productJson->ITENSPLANOPRECOMOVIMENTO[0]->PLANOPRECOPRECOVENDA)){
      $price = $productJson->ITENSPLANOPRECOMOVIMENTO[0]->PLANOPRECOPRECOVENDA;
    }

    $productMagento->setPrice($price);

    $status = 1;
    if($productJson->ITEMINATIVO == 'N' && $preco > 0){
      $status = 1;
    }else{
      $status = 2;
    }

    $productMagento->setStatus($status);


    $productMagento->setCategoryIds($category);


    /*TODO verificar */
    $qty = 0;
    $availableBalance = Mage::getModel('teorema_integration/service_balance')
                                  ->availableBalance($productJson->ITEMREDUZIDO);

    if($availableBalance['success'] && !empty($availableBalance['data'])){
      $qty = $availableBalance['data']->ESTOQUEQUANTIDADEDISPONIVEL;
    }


    $is_in_stock = 0;
    if($qty > 0){
      $is_in_stock = 1;
    }

    $productMagento->setStockData(array(
      'qty' => $qty,
      'manage_stock' => 1,
      'is_in_stock' => $is_in_stock
    ));


    if(isset($productJson->ITEMMEDIDACOMPRIMENTO)){
        $productMagento->setVolumeComprimento($productJson->ITEMMEDIDACOMPRIMENTO);
    }


    if(isset($productJson->ITEMMEDIDAESPESSURA)){
          $productMagento->setVolumeAltura($productJson->ITEMMEDIDAESPESSURA);
    }


    if(isset($productJson->ITEMMEDIDALARGURA)){
        $productMagento->setVolumeLargura($productJson->ITEMMEDIDALARGURA);
    }


    if(isset($productJson->MARCA)){

      echo "<br/>manufacturer is " . $productJson->MARCA . "<br/>";

      $attibuteService = Mage::getModel('teorema_integration/service_attribute');

      $manufacturer = $attibuteService->setAttrtibute('manufacturer', $productJson->MARCA->MARCADESCRICAO);

      $productMagento->setManufacturer($manufacturer);

    }

    return $productMagento ;

  }


  /**
	 * Função que traz todos os produtos do web service Teorema e adiciona no Magento
   * os produtos que ja existem apenas atualiza
	 */
  public function updateAllProductsTeoremaToMagento(){

    /*TODO verificar se o resultado da consulta não sera guardado no banco para uma posteriror carga 'aos poucos'*/

    $resultSearch = $this->getAllProductsToTeorema();

    $cont = 45 ;

    if($resultSearch['success']  && !empty($resultSearch['data']))

    foreach ($resultSearch['data'] as $key => $productTeorema) {

      if($cont <= 60){
        echo "<br/> $cont sku = " . $productTeorema->ITEMREDUZIDO . "<br/>" ;
        //Metodo que ira verificar se o produto existe, se não existir cria o produto..
        $this->createProductMagento($productTeorema->ITEMREDUZIDO); //6745
      }

      $cont++ ;

    }

  }


  /**
   * Função que Traz todos os skus (ITEMREDUZIDO) do web service teorema e insere na tabela teorema_integration_initial
   * utilizado para a carga inicial
   */
  public function chargeSkusTeoremaToInitialModel(){

    echo "<br> \n Carregando todos os valores de ITEMREDUZIDO para o teorema_initial \n";

    $arrayProductsSku = $this->getAllProductsToTeorema();

    echo "<br/> \n Ja temos array com valores\n";

    if($arrayProductsSku['success'] && !empty($arrayProductsSku['data']) ){
      $arrayProductsSku = $arrayProductsSku['data'];
    }else{
      $message = $arrayProductsSku['success']  ? "Não foi encontrados produtos (ITEMREDUZIDO) na consulta Web Service teorema" : "Ocorreu um erro ao fazer a busca no Web service (buscando ITEMREDUZIDO)" ;
      echo $message ;
      $this->saveErrosLog($message, '0', 'product','0', '0');
      exit;
    }

    $i = 0 ;

    foreach ($arrayProductsSku as $key => $p)
    {

      if($i <= $this->limit_load_products_sku){

        try{
          $initial = Mage::getModel('teorema_integration/initial');
          $initial->setSku($p->ITEMREDUZIDO);
          $initial->setStatus('pending');
          $initial->setNumberOfRetries(0);
          $initial->save();

          echo "<br/> \n Criado Initial $i " ;

        }catch(Exception $e){
          //echo "<br/><br/>Error in Creatin Initial " . $e->getMessage();
        }
      }else{
        echo "<br/> \n Finalizado por configurações do modulo.! ";
        break ;
      }
      $i++;
    }

  }

  /**
  *  Função para dar carga inicial dos produtos no magento
  *  Busca todos os skus com status pending e cria
  */
  public function initialCharge(){

    //$this->chargeSkusTeoremaToInitialModel();

      $collection =  Mage::getModel('teorema_integration/initial')->getCollection();
      $collection->addFieldToFilter('status', 'pending')->setPageSize($this->limit_load_products);
      $collection->load();

      echo " \nCarga inicial  \n <br/>";

      $i = 0 ;
      foreach ($collection as $key => $initial)
      {

        echo "<br/> \n Inserindo valores   \n <br/>  ";

        $initial->setNumberOfRetries($initial->getNumberOfRetries() + 1);

        if($initial->getNumberOfRetries() > $this->limit_attempts ){
          $initial->setStatus('error');
        }else{

          $this->saveInitial($initial);
          $product = $this->createProductMagento($initial->getSku());

          if(!is_null($product) && !is_null($product->getId()) ){
            $initial->setStatus('created');
          }

        }

        $this->saveInitial($initial);

        if($i >= $this->limit_load_products){
          echo "<br> \n Processo parado por limite indicado na configuracao do usuario.!";
          break ;
        }

        $i++ ;

      }

  }


public function saveInitial($initial){
  if(!is_null($initial)){
    try{
      $initial->save();
    }catch(Exception $e){
      echo "erro ao salvar " . $e->getMessage();
    }
  }
}

  /**
   * Função responsavel por atualizar ou criar produtos no Magento, que esteja em tabelas alteradas
   * @param $arrayStatus() status do filtro da busca em tableschanged
   * @param $idTableschanged (id tabela modificada) opcional, em caso seja passado sera ul filtro para a busca
   */
  public function updateProductsToTablesChanged($arrayStatus, $idTableschanged ){

    if(is_null($arrayStatus))
      $arrayStatus = array('pending');

    /*TODO verificar que a busca seja por todos pendentes ou processando*/
    $collection =  Mage::getModel('teorema_integration/tableschanged')->getCollection();
    $collection->addFieldToFilter('status', $arrayStatus)->setPageSize($this->indexer_limit);

    if(!is_null($idTableschanged))
      $collection->addFieldToFilter('id', $idTableschanged);

    $collection->addFieldToFilter('type', 'product')->load();

    $tableschangedTeoremaService = Mage::getModel('teorema_integration/service_tableschangedteorema');

     foreach ($collection as $key => $tableschanged)
     {
       $sku = $tableschanged->getIdValue() ;
       echo "<br/> \n Encontramos valores $sku <br/> \n";

       $tableschanged = $tableschangedTeoremaService->sumTableschanged($tableschanged);

       if(!is_null($tableschanged) and $tableschanged->getNumberOfRetries() < $this->limit_attempts and !is_null($sku)  ){

             $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

             if(!$product or is_null($product)){
               echo "<br/> \n Indexer creating product  $sku";
               $product = $this->createProductMagento($sku);
             }else{
               echo "<br/> \n Indexer updating product  $sku";
               $product = $this->getProductOrCreateMagento($sku);

               #produto existe, então sera buscado o Json do mesmo no webService da Teorema..
               $result = $this->getProductJsonToTeorema($sku);
               $productTeorema = null ;
               if($result['success'] && !empty($result['data'])){
                 $productTeorema = $result['data'] ;

                 #Obtendo um novo produto Magento com os valores atualizados do web service teorema
                 $productUpdated   = $this->getNewProductMagentoToJson($productTeorema, $product);

                 $product  = $this->saveProduct($productUpdated);
                 echo "<br/> \n Produto atualizado. <br/> \n";
               }else{
                  $product = null ;
                  $message = !empty($result['message']) ? $result['message'] : "";
                  Mage::getSingleton('core/session')->addError($message);
                  $this->saveErrosLog("Error in update product (updateProductsToTablesChanged) " . $message , '0', 'product', $tableschanged->getLastIdUpdated(), $tableschanged->getId()) ;
               }

             }

             if(!is_null($product) && !is_null($product->getId())){
               $tableschanged->setStatus('processed');
               $tableschanged = $tableschangedTeoremaService->updateTablesChanged($tableschanged);
             }

       }else{
         echo "<br/> \n numero de tentativas excedeu o maximo ";
         $tableschanged->setStatus('error');
         $tableschangedTeoremaService->updateTablesChanged($tableschanged);
       }

     }

  }

  /**
   * Função responsavel por salvar um produto no Magento
   * @param $productMagento produto do tipo magento
   * @return $productMagento
   */
  public function saveProduct($productMagento){
    $productReturn = null ;
    if(!is_null($productMagento)){
      try {
        $productMagento->save();
        $productReturn = $productMagento ;
      } catch (Exception $e) {
        $message = "\nError in save Product\n " . $e->getMessage();
        Mage::log($message, null, 'teorema_insert_error.log');
        echo "<br/> \n " . $message ;
        Mage::getSingleton('core/session')->addError('Erro ao salvar o produto <br/>'. $message );
        $this->saveErrosLog($message , '0', 'product', 0, 0);
      }
    }
    return $productReturn ;
  }


  /**
   * Função responsavel por buscar o produto dentro do Magento, caso o mesmo não exista sera criado..
   * @param $sku do produto
   * @return $productMagento Produto do Magento
   */
  public function getProductOrCreateMagento($sku){
    $product = null ;
    if(!is_null($sku)){
      $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
      /*TODO refactor*/
      if(!$product or is_null($product)){
        $product = $this->createProductMagento($sku);
      }else{
        $product = Mage::getModel('catalog/product')->load($product->getId());
      }
    }
    return $product ;
  }

}
