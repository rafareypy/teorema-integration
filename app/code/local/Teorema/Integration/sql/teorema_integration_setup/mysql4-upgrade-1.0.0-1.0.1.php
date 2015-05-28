<?php
$installer = $this;
$installer->startSetup();

$installer->run("
  DROP TABLE IF EXISTS {$this->getTable('teorema_integration_tables_changed')};
	CREATE TABLE {$this->getTable('teorema_integration_tables_changed')} (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `last_id_updated` int(11) unsigned NOT NULL,
    `status`  ENUM('pending', 'processed', 'closed'),
    `code` int(10) unsigned NOT NULL default '0',
    `type`  ENUM('stock', 'product', 'order', 'customer'),
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
	  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->endSetup();
?>
