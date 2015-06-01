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
  protected $business_number ;
  protected $moving_company ;
  protected $vendor_code ;
  protected $limit_attempts ;
  protected $indexer_limit ;


  /**
   *
   */
  function __construct()
  {
      $this->soapVersion = SOAP_1_1;
      $date = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
      $this->active 		= Mage::getStoreConfig("teorema/teorema_integration/active");
      $this->date 		  = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
      $this->user 		  = Mage::getStoreConfig("teorema/teorema_integration/user");
      $this->password   = $date->format("Y-m-d").'T'.$date->format("H:i:s").'GMT-03:00HOV+01:00';
      $this->password_config = Mage::getStoreConfig("teorema/teorema_integration/password");
      $this->password_md5   = md5($this->password.strtoupper($this->password_config));
      $this->soap_url_wsdl  = Mage::getStoreConfig("teorema/teorema_integration/soap_url_wsdl");
      $this->soap_domain    = Mage::getStoreConfig("teorema/teorema_integration/soap_domain");
      $this->soap_port      = Mage::getStoreConfig("teorema/teorema_integration/soap_port");
      $this->business_number= Mage::getStoreConfig("teorema/teorema_integration/business_number");
      $this->moving_company = Mage::getStoreConfig("teorema/teorema_integration/moving_company");
      $this->vendor_code    = Mage::getStoreConfig("teorema/teorema_integration/vendor_code");
      $this->limit_attempts = Mage::getStoreConfig("teorema/teorema_integration/limit_attempts");
      $this->indexer_limit  = Mage::getStoreConfig("teorema/teorema_integration/indexer_limit");
      $this->cron_limit_search_webservice = Mage::getStoreConfig("teorema/teorema_integration/cron_limit_search_webservice");


      if(is_null($this->limit_attempts))
         $this->limit_attempts = 3 ;

      if(is_null($this->indexer_limit))
         $this->indexer_limit = 30 ;

      if(is_null($this->indexer_limit))
         $this->indexer_limit = 80 ;

     if(is_null($this->cron_limit_search_webservice))
          $this->cron_limit_search_webservice = 50;

  }


  /*TODO melhorar forma de teste com web service*/
  public function connectionIsOk(){
      return $this->connectionGet(null);
  }

  /**
   * @param null $arrayParams
   * @return mixed
   */
  public  function connectionGet( $arrayParams = null){

    $result = array('code' => 'error', 'message' => "error" );


        try {
          $soapClient = new SoapClient(
                $this->soap_url_wsdl,
                array('cache_wsdl' => WSDL_CACHE_NONE, 'encoding' => 'UTF-8','soap_version' => $this->soapVersion));


          /*TODO Verificar se os parametros passados estão corretos*/
          $arrayParams['SENHA'] = $this->password_md5;

          $parameters = json_encode($arrayParams);

          $response = $soapClient->send(array( 'arg0' => $parameters));


          $result = json_decode($response->return) ;


         } catch (Exception $e) {

           /*TODO refatorar*/
             echo "<br/>error when trying to access service.!<br/>";
             echo "Error<br/>";

             var_dump($e);

             $result['message'] = $e->getMessage();
             Mage::log($e->getMessage(), null, 'teorema_integration.log')     ;

         }



         /*TODO verificar retorno, quando der problemas ao fazer uma busca..*/

         if(isset($result->RESULT)){
              return $result->RESULT ;
         }else{
              return $result ;
         }


  }

  public function connectionPost($arrayParams = null){

    $result = array('code' => 'error', 'message' => "error" );


        try {
          $soapClient = new SoapClient(
                $this->soap_url_wsdl,
                array('cache_wsdl' => WSDL_CACHE_NONE, 'encoding' => 'UTF-8','soap_version' => $this->soapVersion));


          /*TODO Verificar se os parametros passados estão corretos*/
          $arrayParams['SENHA'] = $this->password_md5;

          $parameters = json_encode($arrayParams);

          $response = $soapClient->send(array( 'arg0' => $parameters));


          $result = json_decode($response->return) ;


         } catch (Exception $e) {

           /*TODO refatorar*/
             echo "<br/>error when trying to access service.!<br/>";
             echo "Error<br/>";

             var_dump($e);

             $result['message'] = $e->getMessage();
             Mage::log($e->getMessage(), null, 'teorema_integration.log')     ;

         }



         /*TODO verificar retorno, quando der problemas ao fazer uma busca..*/

         if(isset($result->RESULT)){
              return $result->RESULT ;
         }else{
              return $result ;
         }

  }


  /*
    Função responsavel por guardar os logs de erro relacionados ao modulo Teorema Integration..
  */
  public function saveErrosLog($message , $code, $type, $tablesChangedIdTeorema, $idTablesChangedMagento)
  {

    try{


      $errorsModel = Mage::getModel('teorema_integration/errors');

      $errorsModel->setTablesChangedIdTeorema($tablesChangedIdTeorema);
      $errorsModel->setCode($code);
      $errorsModel->setType($type);
      $errorsModel->setMessage($message);
      $errorsModel['id_tables_changed_magento'] = $idTablesChangedMagento;


      #verificar as variaveis se não vem como nulo e adicionanas do atualização do estoque
      $errorsModel->save();

    }catch(Exception $e){
      Mage::log("Error in save log Errors ", null, "service_log_errors.log");
    }


  }

  public function testError(){
    $date = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
  	$user = 'ECOMMERCE';
  	$password = $date->format("Y-m-d").'T'.$date->format("H:i:s").'GMT-03:00HOV+01:00';
  	$password_md5 = md5($password.strtoupper('eco3102'));
  	$soap_url_wsdl = "http://192.168.0.43:5539/send?wsdl";
  	$soap_domain = 'http://192.168.0.43';
  	$soap_port = '5539';
  	$tabelasAlteradas = 'ecomTabelasAlteradas';
  	try {
  			$soapClient = new SoapClient($soap_url_wsdl,array('cache_wsdl' => WSDL_CACHE_NONE, 'encoding' => 'UTF-8','soap_version' => $soapVersion));
        echo "<br/>connected in the service.!";
  			$p = array(
  				 'USUARIO' 	=> $user,
  				 'METODO' 	=> 'ecomItemTodosConsulta',
  				 'SENHA' 	=> $password_md5,
  				 'SISTEMA' 	=> 'ecommerce',
  				 'EMPRESACODIGO' 	=> '0001',
  				 'SENHA_REF' => $password
  			 );
  			  // $p = array(
  				// 'USUARIO' 	=> $user,
  				// 'METODO' 	=> 'ecomTabelasAlteradas',
  				// 'SENHA' 	=> $password_md5,
  				// 'SISTEMA' 	=> 'ecommerce',
  				// 'SENHA_REF' => $password
  			  // );

  			  //Consulta usuarios..
  			  // $p = array(
  				// 'USUARIO' 	=> $user,
  				// 'METODO' 	=> 'ecomConsultaUsuarios',
  				// 'SENHA' 	=> $password_md5,
  				// 'SISTEMA' 	=> 'ecommerce',
  				// 'SENHA_REF' => $password
  			  // );

  			  //Para testar se as configurações estão ok..
  			  //$p = array(
          //'metodo' 	=> 'teste',
          // 'mensagem' 	=> 'testeMs'
          //);
          //$p = array(
          //  'USUARIO'   => $this->user,
          //  'METODO'    => 'ecomItemConsulta',
          //  'SENHA_REF' => $this->password,
          //  'SENHA'     => $this->password_md5,
          //  'EMPRESACODIGO' => '0001',
          //  'ITEMREDUZIDO' => '000001'
          //);
          $parameters = json_encode($p);
  			  $response = $soapClient->send(array( 'arg0' => $parameters));
  			  $arrayResult = json_decode($response->return) ;
  			  //var_dump($arrayResult) ;
  			  foreach ($arrayResult as $key => $users) {
  					   echo "result <br/>---------<br/>";
  					   var_dump($users);
  				     echo "result <br/>---------<br/>";
  			   }

      	}catch (Exception $e) {
      			echo "<br/>error when trying to access service.!<br/>";
      			echo "Error<br/>";
      			var_dump($e);

      	}
      die();
    }

    public function getInfo(){
      return array('date' => $this->date,
                   'password' => $this->password,
                   'user' => $this->user,
                   'password_config' => $this->password_config,
                   'password_md5' => $this->password_md5,
                   'soap_url_wsdl' => $this->soap_url_wsdl,
                   'soap_domain' => $this->soap_domain,
                   'soap_port' => $this->soap_port
            );
    }



}
