<?php
class Teorema_Integration_Model_Cron {

    public function runTablesChanged() {

      Mage::log("atualizando tabelas alteradas Teorema", null, 'runTablesChanged.log')     ;

      $model = Mage::getModel('teorema_integration/indexer_stock');

      $model->reindexAll();

    }

}
