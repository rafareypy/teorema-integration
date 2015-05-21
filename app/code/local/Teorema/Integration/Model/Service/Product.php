<?php
class Teorema_Integration_Model_Service_Product extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

  public function getProduct(){
    return $this->user;
  }



  /*Retorna todos os produtos desde o Web Service Teorema*/
  public function getProductsToTeorema(){


    //Consulta usuarios..
    $p = array(
      'USUARIO' 	=> $this->user,
      'METODO' 	=> $this->allProducts,
      'SENHA' 	=> "",
      'SISTEMA' 	=> 'ecommerce',
      'SENHA_REF' => $this->password
    );


    $result = $this->connectionGet($p);



    return $result ;

  }


}
