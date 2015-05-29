<?php
class Teorema_Integration_Model_Service_Customer extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

  /*Retorna todos os clientes desde o Web Service Teorema*/
  public function getCustomerToTeorema($code = null){

    //Senha sera adicionaod depois on metodo connectionGet
    $param = array(
    			'USUARIO'    => $this->user,
    			'METODO'     => 'ecomClienteConsulta',
    			'CLIFORCODIGO' => $code,
    			'SENHA_REF'  => $this->password,
    			'SENHA'      => "" ,
    			'EMPRESACODIGO' => $this->business_number,
    		);

    $result = $this->connectionGet($param);

    return $result ;

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
  public function createCustomerToTeorema($customer = null){

      /*TODO refactor*/
      $customerObj = $this->getCustomerMagentoToId( $customer->getId() );

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
              'CLIFORBAIRRO' => $neighborhood, //$billingAddress['neighborhood'],
              'EMPRESACODIGO' => $this->business_number,
              'MUNICIPIOCODIGOIBGE' => 09401,
              'CLIFORCEP' => $billingAddress['postcode'],
              'CLIFORTELEFONE' => $billingAddress['telephone'],
              'CLIFORENDERECONRO' => $number,
              'CLIFORINSCRICAOESTADUALRG' => $customerObj['taxvat'],
              'CLIFORNOME' => $customer->getData('firstname') . " " . $customer->getData('lastname'),
              'CLIFOREMAIL' => $customer->getData('email'),
              'USUARIO' => $this->user,
              'SENHA'     => $this->password_md5,
              'SENHA_REF' => $this->password,
            );


            $result = $this->connectionGet($params);

            #Obtemos o codigo do cliente desde teorema e adicionamos ao cliente Magento
            if(isset($result->CODIGO)){
              if($result->CODIGO == 0){
                echo "<br/>Customer " . $result->FIELDS->CLIFORCODIGO . " saved " ;


              }
            }

            return $result ;

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


}
