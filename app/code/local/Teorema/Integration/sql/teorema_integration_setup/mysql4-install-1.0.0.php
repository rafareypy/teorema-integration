<?php

$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('teorema_integration');

$sql=<<<SQLTEXT
CREATE TABLE `{$tableName}` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `email` varchar(64) CHARACTER SET utf8 NOT NULL,
  `text` varchar(2048) CHARACTER SET utf8 NOT NULL,
  `reviewed` BOOL NOT NULL DEFAULT '0' ,
  `ranking` ENUM('high','medium','low') NOT NULL DEFAULT 'low',
  `city` varchar(25) CHARACTER SET utf8 NOT NULL,
  `state` varchar(25) CHARACTER SET utf8 NOT NULL,
  `state_abb` char(2) CHARACTER SET utf8 NOT NULL,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
