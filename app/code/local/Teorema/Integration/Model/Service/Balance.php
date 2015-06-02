<?php
class Teorema_Integration_Model_Service_Balance extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

  /* Reserva produto no WebService Teorema
     Exemplo de retorno { "SEQUENCIA": 1 "CODIGO" :0 }
  */
  public function reservedBalanceToProduct($sku){

    /*TODO verificar se sku não esta nulo*/

    $params = array(
      'USUARIO'   => $this->user,
      'METODO'    => 'ecomItemSaldoReserva',
      'QUANTIDADERESERVADA'    => 1 ,
      'EMPRESAITEM' => '0001',
      'ITEMREDUZIDO' => $sku,
      'SENHA'     => $this->password_md5,
      'EMPRESAMOVIMENTO' =>  $this->moving_company,
      'SENHA_REF' => $this->password,
    );

    return $this->connectionGet($params);

  }

  /*Busca o estoque disponivel para o produto
    bs: estoquequantidadedisponivel = (estoquequantidade - estoquequantidadereservada)
    Exemplo de retorno:
    { ["ESTOQUEQUANTIDADE"]=> int(0) ["CODIGO"]=> int(0) ["ESTOQUEQUANTIDADEDISPONIVEL"]=> int(0) ["ESTOQUEQUANTIDADERESERVADA"]=> int(0) }
  */
  public function availableBalance($sku = null){

    $params = array(
      'USUARIO'   => $this->user,
      'METODO'    => 'ecomItemSaldoDisponivel',
      'EMPRESAITEM' => '0001',
      'ITEMREDUZIDO'  => $sku,
      'SENHA'     => $this->password_md5,
      'EMPRESAMOVIMENTO' =>  $this->moving_company,
      'SENHA_REF' => $this->password
    );

    return $this->connectionGet($params);

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

    return $this->connectionGet($param);

  }


}
