<?php
$installer = $this;
$installer->startSetup();

$installer->run("
  DROP TABLE IF EXISTS {$this->getTable('teorema_integration_errors')};
	CREATE TABLE {$this->getTable('teorema_integration_errors')} (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `tables_changed_id_teorema` int(11) unsigned NOT NULL,
    `id_tables_changed_magento` int(11) unsigned NOT NULL,
    `code` int(10) unsigned NOT NULL default '0',
    `type`  ENUM('stock', 'product', 'order', 'customer', 'other', 'item_plan_price_movement', 'payment_condition', 'business', 'mark'),
    `message` varchar (255) default NULL,
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
	  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->endSetup();
?>
