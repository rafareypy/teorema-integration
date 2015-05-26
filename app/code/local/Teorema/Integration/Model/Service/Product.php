<?php
class Teorema_Integration_Model_Service_Product extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }



# kit, é um agrupamento de produtos sem atributo
# agrupado é usando 1 atributo
# e configurável é usando 1 ou mais atributos

  /*Retorna todos os produtos desde o Web Service Teorema*/
  public function getProductsToTeorema(){

    //Senha sera adicionaod depois on metodo connectionGet
    $p = array(
      'USUARIO' 	=> $this->user,
      'METODO' 	  => 'ecomItemTodosConsulta',
      'SENHA'     => "",
      'SISTEMA' 	=> 'ecommerce',
      'SENHA_REF' => $this->password
    );

    $result = $this->connectionGet($p);

    return $result ;

  }

  /*
    Metodo que busca um determinado produto no Web Service Teorema
    $sku = ITEMREDUZIDO
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

  /*
    Traz todos os codigos 'ITEMREDUZIDO' do webservice Teorema
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


  /*
    Web service (ecomItemTodosGrade) Teorema que retorna toda a grade de produtos
  */
  public function getAllGroupedProductToTeorema(){
    $params = array(
			'USUARIO'  => $this->user,
			'METODO'   => 'ecomItemTodosGrade',
			'SENHA_REF'=> $this->password,
			'SENHA'    => $this->password_md5,
			'EMPRESACODIGO' => '0001'
		);

		return $this->connectionGet($params);
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


  public function createProductMagento($sku)
  {

    #Verifica se o produto existe
    $product_exists = Mage::getModel('catalog/product')
                        ->loadByAttribute('sku', $sku);


    if($product_exists){
      echo "<br> Product  existis $sku <br/> ------";
    }else{
      echo "<br/>Creating product<br/>";

        #Se o produto não existe, então sera buscado o Json do mesmo no webService da Teorema..
        $productTeorema = $this->getProductJsonToTeorema($sku);

        #Obtendo um novo produto Magento
        $productMagentoNew     = $this->getNewProductMagentoToJson($productTeorema);

        //006733
        // ["ITEMDESCRICAO2"]=> string(31) "CAJON SPANDER S003 LUXO NATURAL"
        try {

          $productMagentoNew->save();
          Mage::log("Produto " . $productMagentoNew->getSku() . " criado com sucesso. ", null, 'teorema_insert.log');
          echo "<br/>Produto " . $productMagentoNew->getSku() . " criado com sucesso. ";
        } catch (Exception $e) {
          //Mage::log($e->getMessage());
          echo "<br/>Error in save Product <br/>";
          echo "<br/>" . $e->getMessage() ;
          echo "<br/>";
        }

    }


  }

  /*
    Função responsavel por criar produto Magento
    Recebe como parametro o objeto json Teorema webService
  */
  public function getNewProductMagentoToJson($productJson){


    //$category = array(0 => "2" );

    $category = array(1, 3);

    //{ [0]=> string(1) "2" [1]=> string(1) "3" [2]=> string(1) "4" }

    $productMagento = Mage::getModel('catalog/product');

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


    $qty = 0;
    $availableBalance = Mage::getModel('teorema_integration/service_balance')
                                  ->availableBalance($productJson->ITEMREDUZIDO);

    if($availableBalance){
      $qty = $availableBalance->ESTOQUEQUANTIDADEDISPONIVEL;
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


    echo "<br/>Product sku = " . $productMagento->getSku();
    echo "<br/>Product Description = " . $productMagento->getName();


    return $productMagento ;

  }

  /*
    Função que traz todos os produtos do web service Teorema e adiciona no Magento
    os produtos que ja existem apenas atualiza
  */
  /*TODO verificar se esta função tbm ira atualizar o estoque dos produtos*/
  public function updateAllProductsTeoremaToMagento(){

    /*TODO verificar se o resultado da consulta não sera guardado no banco para uma posteriror carga 'aos poucos'*/

    $resultSearch = $this->getAllProductsToTeorema();

    $cont = 10 ;


    foreach ($resultSearch as $key => $productTeorema) {


      if($cont <= 37){
        echo "<br/>sku = " . $productTeorema->ITEMREDUZIDO . "<br/>" ;

        //Metodo que ira verificar se o produto existe, se não existir cria o produto..
        //$this->createProductMagento($productTeorema->ITEMREDUZIDO); //6745
      }



      $cont++ ;

    }




  }


  public  function test(){

     $p = array(
         'USUARIO' 	=> $this->user,
         'METODO' 	=> 'ecomConsultaUsuarios',
         'SENHA' 	  => $this->password ,
         'SISTEMA' 	=> 'ecommerce',
         'SENHA_REF' => $this->senha,
       );

       $result = $this->connectionGet($params);

  }






}
