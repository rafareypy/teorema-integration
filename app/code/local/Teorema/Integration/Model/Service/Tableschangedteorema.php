<?php
class Teorema_Integration_Model_Service_Tableschangedteorema extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

  /**
   * Função que busca todos os dados de tabelas alteradas no web service Teorema
   * @param $idMini id inicial para a busca dentro do w.s.
   * @param $idmax id maximo para busca no w.s.
   * @param $idMini id inicial para a busca dentro do w.s.
   * @return JSON ecomTabelasAlteradas
   */
  public function getTablesChanged($idMini , $idmax , $value ){

    if( is_null($idMini) )
      $idMini = 1 ;

    if( is_null($idmax) )
      $idmax = 9999999999 ;

      $params = array(
      'USUARIO'   => $this->user,
      'METODO'    => 'ecomTabelasAlteradas',
      'IDINI'     => $idMini,
      'IDMAX'     => $idmax,
      'SENHA_REF' => $this->password,
      'SENHA'     => $this->password_md5,
      'EMPRESACODIGO' => $this->business_number,

    );

    return $this->connectionGet($params);


  }

  /**
   * Função  que sincroniza tabelas modificadas do Web service teorema
   * com Magento
   * @return JSON ecomTabelasAlteradas
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
        foreach ($tablesTeoremaChangedList['data'] as $key => $table)
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
    }else if(!$tablesTeoremaChangedList['success']){
      Mage::getSingleton('adminhtml/session')->addWarning('Erro ao consultar valores de tabelas alteradas <br> ' .$tablesTeoremaChangedList['message'] );
      $this->saveErrosLog("Error in update tables changed " .$tablesTeoremaChangedList['message'] , '0', 'tableschanged', '0', '0') ;
    }
  }



  /**
   * Função  soma a quantidade de tentativas de execução dos registros em tabelas alteradas
   * @param $tableschanged registro de tabela alterada a ser somado NumberOfRetries
   * @return $tableschanged
   */
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

    /**
     * Função  soma a quantidade de tentativas de execução dos registros em tabelas alteradas
     * @param $tableschanged registro de tabela alterada a ser atualizado
     * @return $tableschanged
     */
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
