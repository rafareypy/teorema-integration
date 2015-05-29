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

  /*
    Função  que sincroniza tabelas modificadas do Web service teorema
    com o Magento..
  */
  public function updateTablesChangedTeorema(){

    $limit = Mage::getStoreConfig("teorema/teorema_integration/indexer_limit");
    if(is_null($limit))
       $limit = 80 ;


    $tablesMagentochanged = Mage::getResourceModel('teorema_integration/tableschanged_collection')
                                  ->setOrder('id', 'desc')
                                  ->setPageSize($limit);

    $table = $tablesMagentochanged->getFirstItem();

    $limitWebservice = Mage::getStoreConfig("teorema/teorema_integration/cron_limit_search_webservice");

    if(is_null($limitWebservice))
    $limitWebservice = 50 ;

    #Buscando tabelas alteradas
    $tablesTeoremaChangedList = $this->getTablesChanged($table->getLastIdUpdated(), $limitWebservice, null);


    foreach ($tablesTeoremaChangedList as $key => $table)
    {

        $type = 'other';

        $id_value = "000" ;

        $tableschanged = Mage::getModel('teorema_integration/tableschanged');

        $tableschanged->setLastIdUpdated($table->ID);
        $tableschanged->setStatus('pending');

        switch ($table->TABELA)
        {

          #verificar quais adicionar

          case "ItemEstoque":
              $type = 'stock' ;
              $id_value = $table->ITEMREDUZIDO;
              echo "<br/>Item reduzido<br/>";
              echo $table->ITEMREDUZIDO ;
            break;
          case "ClienteFornecedor":
              $type = 'customer';
              $id_value = $table->CLIFORCODIGO;
            break;

          case "Item":
            $type = 'product';
            $id_value = $table->ITEMREDUZIDO;
          break;


          case "ItemPlanoPrecoMovimento":
            $type = 'item_plan_price_movement';
            $id_value = $table->PLANOPRECOSEQUENCIA;
          break;

          case "CondicaoPagamento":
            $type = 'payment_condition';
            $id_value = $table->CONDICAOCODIGO;
          break;

          case "Empresa":
            $type = 'business';
            $id_value = $table->EMPRESACODIGO;
          break;

          case "Marca":
            $type = 'mark';
            $id_value = $table->MARCACODIGO;
          break;

        }

        $tableschanged->setDate(new Date());
        $tableschanged->setType($type);
        $tableschanged->setIdValue($id_value);

        echo "<br/>" .  $tableschanged->getType() . "<br/>";

        $tableschanged->save();


    }



  }


}
