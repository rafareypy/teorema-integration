<?php
$installer = $this;
$installer->startSetup();

$installer->run("
  DROP TABLE IF EXISTS {$this->getTable('teorema_integration_initial')};
	CREATE TABLE {$this->getTable('teorema_integration_initial')} (
    `id` int(11) unsigned NOT NULL auto_increment,
    `sku` varchar (64) NOT NULL ,
    `status`  ENUM('pending', 'created', 'error'),
    `number_of_retries` int(3) unsigned NOT NULL default '0',
    `message` varchar (255) default NULL,
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
	  PRIMARY KEY (`id`),
    UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->endSetup();
?>
