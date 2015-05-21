<?php
abstract class Teorema_Integration_Model_Service extends Mage_Core_Model_Abstract
{

  /*TODO verificar necessidade da variavel data*/
  //private $date ;
  protected $soapVersion ;
  protected $user ;
  protected $password ;
  /*TODO verificar variaveis diminuir para apenas uma*/
  protected $password_config ;
  protected $password_md5 ;
  protected $soap_url_wsdl ;
  protected $soap_domain ;
  protected $soap_port ;
  public    $allProducts ;


  /**
   *
   */
  function __construct()
  {
      echo "construct Service Teorema<br/>";

      $this->soapVersion = SOAP_1_1;

      $date = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));

      $this->active 		= Mage::getStoreConfig("catalog/pcomsoftvar/active");

      $this->date 		  = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
      $this->user 		  = Mage::getStoreConfig("catalog/teorema_integration/user");
      $this->password   = $date->format("Y-m-d").'T'.$date->format("H:i:s").'GMT-03:00HOV+01:00';
      $this->password_config = Mage::getStoreConfig("catalog/teorema_integration/password");
      $this->password_md5   = md5($this->password.strtoupper($this->password_config));
      $this->soap_url_wsdl  = Mage::getStoreConfig("catalog/teorema_integration/soap_url_wsdl");
      $this->$soap_domain   = Mage::getStoreConfig("catalog/teorema_integration/soap_domain");
      $this->soap_port      = Mage::getStoreConfig("catalog/teorema_integration/soap_port");
      $this->allProducts    = 'ecomItemTodosConsulta';

      //echo "<br/>...............<br/>";
      //echo "<br/>  Date : " . $this->date ;
      //echo "<br/>  Password : " . $this->password ;
      //echo "<br/>  User : " . $this->user ;
      //echo "<br/>  password_conig : " . $this->password_conig ;
      //echo "<br/>  password_md5 : " . $this->password_md5 ;
      //echo "<br/>  soap_url_wsdl : " . $this->soap_url_wsdl ;
      //echo "<br/>  soap_domain : " . $this->soap_domain ;
      //echo "<br/>  soap_port : " . $this->soap_port ;
      //echo "<br/>...............<br/>";

  }




  /*TODO melhorar forma de teste com web service*/
  public function connectionIsOk(){

    $resultTest = false ;

      try{

        //Para testar se as configurações estão ok..
			 $p = array(
				 'metodo' 	=> 'teste',
				 'mensagem' 	=> 'testeMs'
			 );

        $soapClient = new SoapClient(
        $this->soap_url_wsdl,
          array('cache_wsdl' => WSDL_CACHE_NONE,
          'encoding' => 'UTF-8','soap_version' => $soapVersion));

        $parameters = json_encode($p);

        $response = $soapClient->send(array( 'arg0' => $parameters));

        $result = json_decode($response->return) ;

        echo "+++<br/>" ;
        echo $result ;
        echo ".........." ;

        $resultTest = true ;

      }catch(Exception $e){
        echo "Error in testing WebService";
        echo "<br/>";

      }

      return $resultTest ;

  }

  /**
   * @param null $params
   * @return mixed
   */
  public  function connectionGet( $arrayParams = null, $method  = null){

    $result = array('code' => 'error' );

        try {
          $soapClient = new SoapClient(
                $this->soap_url_wsdl,
                array('cache_wsdl' => WSDL_CACHE_NONE, 'encoding' => 'UTF-8','soap_version' => $this->soapVersion));


          /*TODO Verificar se os parametros passados estão corretos*/
          $p['SENHA'] = $this->password_md5;


          $parameters = json_encode($arrayParams);

          $response = $soapClient->send(array( 'arg0' => $parameters));

          $result = json_decode($response->return) ;


         } catch (Exception $e) {

             echo "<br/>error when trying to access service.!<br/>";
             echo "Error<br/>";
             var_dump($e);

         }

         return $result ;

  }



}
