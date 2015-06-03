<?php
class Teorema_Integration_Model_Service_Customer extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

  /*Retorna clientes desde o Web Service Teorema*/
  public function getCustomerToTeorema($code ){

    //Senha sera adicionaod depois on metodo connectionGet
    $params = array(
    			'USUARIO'    => $this->user,
    			'METODO'     => 'ecomClienteConsulta',
    			'CLIFORCODIGO' => $code,
    			'SENHA_REF'  => $this->password,
    			'SENHA'      => "" ,
    			'EMPRESACODIGO' => $this->business_number,
    		);

    return  $this->connectionGet($params);

  }

  public function getAllCustomersToTeorema(){

    $params = array(
    			'USUARIO'    => $this->user,
    			'METODO'     => 'ecomClienteTodosConsulta',
          'SENHA'      => "" ,
          'EMPRESACODIGO' => $this->business_number,
          'SENHA_REF'  => $this->password,
    		);

    return $this->connectionGet($params);

  }

  /*
    Função que cria um cliente Magento para o webService teorema (ecomClienteAltera)
  */
  public function createCustomerMagentoToTeorema($customer){

    /*TODO refactor
      verificar melhhor forma de envio cliente, em caso de erro por que o mesmo ja existe ou atualizaçao etc
    */

      $customerObj = $this->getCustomerMagentoToId( $customer->getId() );

      if(!$customerObj or is_null($customerObj) ){
        $this->saveErrosLog("Cliente Magento nulo para tentativa de envio w.s. teorema", '0', 'customer', '0', '0');
        return $customerObj ;
      }


      if(!is_null($customerObj->getTeoremaCode()) && $customerObj->getTeoremaCode() != "" ){
        #em caso que o cliente ativou funcionalidade de atualizar cliente executa o meso..
        if($this->update_customer){

        }
      }else{

        $params = $this->getParamsCreateCustomerTeoremaToMagento($customerObj);

        $result = $this->connectionPost($params);

        if($result['success'] && !empty($result['data'])){
          $result = $result['data'] ;

          if(isset($result->CODIGO)){
            if($result->CODIGO == 0){
              $customerObj->setTeoremaCode($result->FIELDS->CLIFORCODIGO);

              $customerObj = $this->saveCustomer($customerObj);
            }
          }
        }else{
          $message = "Erro ao tentar salvar um cliente Magento no w.s. Teorema " . $result['message'];
          $this->saveErrosLog($message, '0', 'customer', '0', '0');
        }

      }

      return $customerObj ;

  }


  /*
    Função que 'monta' array com parametros para que possa ser inserido no w.s. teorema
  */
  public function getParamsCreateCustomerTeoremaToMagento($customerObj)
  {

    $billingAddress  = $customerObj->getDefaultBillingAddress() ;

    $number = $this->getParamAddress($billingAddress, 2) ;

    $neighborhood = $this->getParamAddress($billingAddress, 3) ;

    if(is_null($neighborhood) or $neighborhood == "" )
      $neighborhood = "nd";

    /*
      TODO obter o 'tipo pessoa desde o objeto Customer'
      verificar esquema com codigo do mounicipio
      verificar usando o metodo getParamAddress obtendo todos os dados do endereco
    */

    $cliforfisicojuridico = $customerObj['tipopessoa'] > 232 ? "f" : "j";

    #obter os endereços do magento para adicionar ao teorema
    $params = array(
            'METODO' => 'ecomClienteAltera',
            'CLIFORCODIGO'	=> '',
            'CLIFORFISICOJURIDICO' => $cliforfisicojuridico,
            'CLIFORENDERECO' => $billingAddress['street'],
            'CLIFORDOCUMENTO' => $customerObj['taxvat'],
            'CLIFORBAIRRO' => $neighborhood, 
            'EMPRESACODIGO' => $this->business_number,
            'MUNICIPIOCODIGOIBGE' => 09401,
            'CLIFORCEP' => $billingAddress['postcode'],
            'CLIFORTELEFONE' => $billingAddress['telephone'],
            'CLIFORENDERECONRO' => $number,
            'CLIFORINSCRICAOESTADUALRG' => $customerObj['taxvat'],
            'CLIFORNOME' => $customerObj->getData('firstname') . " " . $customerObj->getData('lastname'),
            'CLIFOREMAIL' => $customerObj->getData('email'),
            'USUARIO' => $this->user,
            'SENHA'     => $this->password_md5,
            'SENHA_REF' => $this->password,
          );

          return $params ;
  }

  /**
	 * Função que retorna Endereço, desde o objeto endereço e parametro,
	 * caso nao encontre o enderço pelo padrão do Magento onrem $paramNumber eh o numero de cada atributo
	 * ele trara o enderço pelo padrão PontoCom
	 * @param Objeto Endereço $address
	 * @param Parametro do endereço (magento) $paramNumber
	 * @return Endereço
	 */
	public function getParamAddress($address, $paramNumber)
	{
		$paramAddress = null ;

		if(!is_null($address->getStreet($paramNumber)))
		{
			$paramAddress = $address->getStreet($paramNumber) ;
		}
		else
		{
			switch ($paramNumber)
			{
				case 1:
					if(!is_null($address->getStreet()))
					{
						$paramAddress = $address->getStreet() ;
					}
					break;
				case 2:
					if(!is_null($address->getStreetNumber()))
					{
						$paramAddress = $address->getStreetNumber() ;
					}
					break;
				case 3:
					if(!is_null($address->getBairro()))
					{
						$paramAddress = $address->getBairro() ;
					}
					break;
			}
		}

		return substr($paramAddress, 0,28);

	}


  public function getCustomerMagentoToId($id = null){
    $customer = null ;
    if(!is_null($id)){
      $customer = Mage::getModel('customer/customer')->load($id);
    }
    return $customer ;
  }

  public function saveCustomer($customer){

    if(!is_null($customer)){
      try{
        $customer->save();
      }catch(Exception $e){
        $id = $customer->getId() ;
        $message = "Erro ao tentar salvar cliente $id <br/>" . $e->getMessage() ;
        $this->saveErrosLog($message, '0', 'customer', '0', '0');
      }
    }
    return $customer ;
  }



}
