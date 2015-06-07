<?php

$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('teorema_cart');

$sql=<<<SQLTEXT
CREATE TABLE `{$tableName}` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `customer_id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `email` varchar(64) CHARACTER SET utf8 NOT NULL,    
  `status` ENUM('active','closed', 'sold') NOT NULL DEFAULT 'active',
  `cart_id` varchar(25) CHARACTER SET utf8 NOT NULL,  
  `increment_id` varchar(25) CHARACTER SET utf8 ,   
  `grand_total` DOUBLE ,  
  `products_id` varchar(25) CHARACTER SET utf8 ,   
  `number_of_retries` INT DEFAULT 0 ,  
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB;
SQLTEXT;


$installer->run($sql);

$installer->endSetup();
