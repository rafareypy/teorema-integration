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

/* Customer */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);


//add teorema code client
$setup->addAttribute('customer', 'teorema_code', array(
    'type' => 'int',
    'input' => 'text',
    'label' => 'Teorema Codigo',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 0,
    'comment' => 'Código do cliente no sistema de gestão da teorema'
));

$setup->addAttributeToGroup(
	$entityTypeId,
	$attributeSetId,
	$attributeGroupId,
	'teorema_code',
	'999' //sort_order
);

Mage::getSingleton('eav/config')
		->getAttribute('customer', 'teorema_code')
		->setData('used_in_forms', array('customer_account_create','customer_account_edit','adminhtml_customer'))
		->save();

$installer->endSetup();
