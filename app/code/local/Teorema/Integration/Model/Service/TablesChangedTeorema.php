<?php
class Teorema_Integration_Model_Service_TablesChangedTeorema extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }


  /*
    Obtem todos os dados de tabelas alteradas no web service Teorema
  */
  public function getTablesChanged($idMini = 1, $idmax = 500, $value = null){

    $p = array(
      'USUARIO'   => $this->user,
      'METODO'    => 'ecomTabelasAlteradas',
      'IDINI'     => 1,
      'IDMAX'     => $idmax,
      'SENHA_REF' => $this->password,
      'SENHA'     => $this->password_md5,
      'EMPRESACODIGO' => '0001',

    );

    return $this->connectionPost($p);

  }

  public function updateTablesChangedTeorema(){

          $tablesMagentochanged = Mage::getResourceModel('teorema_integration/tableschanged_collection')
                                  ->setOrder('id', 'desc')
                                  ->setPageSize(1);

        $table = $tablesMagentochanged->getFirstItem();


    $tablesTeoremaChangedList = $this->getTablesChanged($table->getLastIdUpdated(), 2, null);

    foreach ($tablesTeoremaChangedList as $key => $table)
    {

        $type = 'product';

        $tableschanged = Mage::getModel('teorema_integration/tableschanged');

        $tableschanged->setLastIdUpdated($table->ID);
        $tableschanged->setStatus('pending');

        switch ($table->TABELA)
        {
          case "ItemEstoque":
              $type = 'stock' ;
            break;
          case "ClienteFornecedor":
              $type = 'customer';
            break;
        }

        $tableschanged->setDate(new Date());
        $tableschanged->setType($type);

        echo "<br/>" .  $tableschanged->getType() . "<br/>";

        $tableschanged->save();


    }



  }


}
