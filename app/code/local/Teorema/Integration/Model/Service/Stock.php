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

        $p = array(
          'USUARIO'   => $this->user,
          'METODO'    => 'ecomTabelasAlteradas',
          'IDINI'     => 1,
          'IDMAX'     => 100,
          'SENHA_REF' => $this->password,
          'SENHA'     => $this->password_md5,
          'EMPRESACODIGO' => '0001',

        );

  		$test =  $this->connectionPost($p);

      echo "Result ";

      var_dump( json_encode($test));

  }

}
