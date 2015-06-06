<?php
$installer = $this;
$installer->startSetup();

$installer->run("
  DROP TABLE IF EXISTS {$this->getTable('teorema_cart_tables_changed')};
	CREATE TABLE {$this->getTable('teorema_cart_tables_changed')} (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `last_id_updated` int(11) unsigned NOT NULL,
    `status`  ENUM('pending', 'processed', 'processing'),
    `code` int(10) unsigned NOT NULL default '0',
    `number_of_retries` int(3) unsigned NOT NULL default '0',
    `id_value` varchar (64) default NULL,
    `type`  ENUM('stock', 'product', 'order', 'customer'),
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
	  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->endSetup();
?>
