<?php
class Teorema_Integration_Model_Indexer_Product extends Mage_Index_Model_Indexer_Abstract
{

  const EVENT_MATCH_RESULT_KEY = 'teorema_integration_match_result';

  /**
   * @var array
   */
  protected $_matchedEntities = array(
      Mage_Catalog_Model_Product::ENTITY => array(
          Mage_Index_Model_Event::TYPE_SAVE,
          Mage_Index_Model_Event::TYPE_MASS_ACTION,
          Mage_Index_Model_Event::TYPE_DELETE
      )
  );

  /**
  * Nome do Indexer
  */
  public function getName()
  {
      return 'Teorema-Integração';
  }

  /**
  * Descricao do Indexer
  */
  public function getDescription()
  {
      return 'Sincroniza produtos Sistema Teorema Magento.!';
  }


      protected function _registerEvent(Mage_Index_Model_Event $event)
      {
        Mage::log("_registerEvent", null, "indexer.log");


      }

      /**
       * Process event
       * @param Mage_Index_Model_Event $event
       */
      protected function _processEvent(Mage_Index_Model_Event $event)
      {
        Mage::log("_processEvent", null, "indexer.log");



      }


      /**
       * match whether the reindexing should be fired
       * @param Mage_Index_Model_Event $event
       * @return bool
       */
      public function matchEvent(Mage_Index_Model_Event $event)
      {
          Mage::log("matchEvent", null, "indexer.log");
      }

      /**
       * Ação que recebe quando o usuário clica em reindexar no painel do Magento
       * neste caso ira atualizar todos os estoques dos produtos,
       * que foram alteerados no registro de tabelas altedas do WebService Teorema
       */
      public function reindexAll()
      {
          $this->updateProductsToTablesChanged();
      }


      public function updateProductsToTablesChanged()
      {
        $serviceProduct = Mage::getModel('teorema_integration/service_product');

        if($serviceProduct->getStatusModule()){


          $serviceProduct->updateProductsToTablesChanged(array('processing','pending'));
        }else{
          Mage::getSingleton('adminhtml/session')->addWarning('Modulo Teorema Integração esta desativado.!');
        }
      }


}
