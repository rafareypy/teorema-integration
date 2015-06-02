<?php
class Teorema_Integration_Model_Service_Tableschangedteorema extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }


  /*
    Obtem todos os dados de tabelas alteradas no web service Teorema
  */
  public function getTablesChanged($idMini , $idmax , $value = null){

    if( is_null($idMini) )
      $idMini = 1 ;

    if( is_null($idmax) )
      $idmax = 30 ;

    $p = array(
      'USUARIO'   => $this->user,
      'METODO'    => 'ecomTabelasAlteradas',
      'IDINI'     => $idMini,
      'IDMAX'     => $idmax,
      'SENHA_REF' => $this->password,
      'SENHA'     => $this->password_md5,
      'EMPRESACODIGO' => $this->business_number,

    );

    return $this->connectionPost($p);

  }

  /*
    Função  que sincroniza tabelas modificadas do Web service teorema
    com o Magento..
  */
  public function updateTablesChangedTeorema(){

    $tablesMagentochanged = Mage::getResourceModel('teorema_integration/tableschanged_collection')
                                  ->setOrder('id', 'desc')
                                  ->setPageSize($this->indexer_limit);

    #Otendo o ultimo registos de tabelas alteradas..
    $table = $tablesMagentochanged->getFirstItem();

    /*refactor*/
    $idMini = 1 ;

    if(!is_null($table) && !is_null($table->getLastIdUpdated()))
      $idMini = $table->getLastIdUpdated() + 1 ;

    #Buscando tabelas alteradas
    $tablesTeoremaChangedList = $this->getTablesChanged($idMini , $this->cron_limit_search_webservice, null);

    if($tablesTeoremaChangedList['success'] && !empty($tablesTeoremaChangedList['data'])){
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



    public function sumTableschanged($tableschanged){

      if(is_null($tableschanged))
        return null ;

      #Verifica limite de tentativas para atualizar..
      $tableschanged->setNumberOfRetries($tableschanged->getNumberOfRetries() + 1);


      if($tableschanged->getNumberOfRetries() < ($this->limit_attempts + 1)){
        $tableschanged->setStatus('processing');
        $this->updateTablesChanged($tableschanged);
      }

      return $tableschanged ;

    }


    public function updateTablesChanged($tableschanged){

      try{
        $tableschanged->save();
      }catch(Exception $e){
        $tableschanged = null ;

        $message = " Teorema_Integration_Model_Service_TablesChangedTeorema : Error in update tableschanged  " . $e->getMessage() ;

        $this->saveErrosLog($message, '0', 'tableschanged', $tableschanged->getLastIdUpdated(), $tableschanged->getId());

        Mage::log($message, null, "update_stock_error.log");

      }

      return $tableschanged ;
    }

}
