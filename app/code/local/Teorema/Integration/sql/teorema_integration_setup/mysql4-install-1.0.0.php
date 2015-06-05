<?php

$installer = $this;

$installer->startSetup();

/*Criando tabela teorema_integration*/
$sql=<<<SQLTEXT
CREATE TABLE `{$installer->getTable('teorema_integration')}` (
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


/*Criando tabela teorema_integration_tables_changed*/
$sql=<<<SQLTEXT
CREATE TABLE `{$this->getTable('teorema_integration_tables_changed')}` (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `last_id_updated` int(11) unsigned NOT NULL,
    `status`  ENUM('pending', 'processed', 'processing'),
    `code` int(10) unsigned NOT NULL default '0',
    `number_of_retries` int(3) unsigned NOT NULL default '0',
    `id_value` varchar (64) default NULL,
    `type`  ENUM('stock', 'product', 'order', 'customer', 'other', 'item_plan_price_movement', 'payment_condition', 'business', 'mark'),
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
	  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQLTEXT;
$installer->run($sql);

/*Criando tabela teorema_integration_errors*/
$sql=<<<SQLTEXT
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQLTEXT;
$installer->run($sql);

/*Criando tabela teorema_integration_initial*/
$sql=<<<SQLTEXT
CREATE TABLE `{$this->getTable('teorema_integration_initial')}` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `sku` varchar (64) NOT NULL ,
    `status`  ENUM('pending', 'created', 'error'),
    `number_of_retries` int(3) unsigned NOT NULL default '0',
    `message` varchar (255) default NULL,
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
	  PRIMARY KEY (`id`),
    UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQLTEXT;
$installer->run($sql);

/* Customer */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->startSetup();

$customer_entityTypeId     = $setup->getEntityTypeId('customer');
$customer_attributeSetId   = $setup->getDefaultAttributeSetId($customer_entityTypeId);
$customer_attributeGroupId = $setup->getDefaultAttributeGroupId($customer_entityTypeId, $customer_attributeSetId);

//add teorema code client
$setup->addAttribute('customer', 'teorema_code', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Teorema Codigo',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 0,
    'comment' => 'Código do cliente no sistema de gestão da teorema'
));

$setup->addAttributeToGroup(
	$customer_entityTypeId,
	$customer_attributeSetId,
	$customer_attributeGroupId,
	'teorema_code',
	'999' //sort_order
);

Mage::getSingleton('eav/config')
		->getAttribute('customer', 'teorema_code')
		->setData('used_in_forms', array('customer_account_create','customer_account_edit','adminhtml_customer'))
		->save();

$setup->endSetup();

$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');

$setup->startSetup();

$category_entityTypeId     = $setup->getEntityTypeId('catalog_category');
$category_attributeSetId   = $setup->getDefaultAttributeSetId($category_entityTypeId);
$category_attributeGroupId = $setup->getDefaultAttributeGroupId($category_entityTypeId, $category_attributeSetId);

$setup->addAttribute('catalog_category', 'category_teorema',  array(
    'type'     => 'int',
    'label'    => 'Categoria da Teorema',
    'input'    => 'select',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => true,
    'user_defined'      => false,
    'default'           => 0,
    'source' => 'eav/entity_attribute_source_boolean',
));


$setup->addAttributeToGroup(
    $category_entityTypeId,
    $category_attributeSetId,
    $category_attributeGroupId,
    'category_teorema',
    '11'					//last Magento's attribute position in General tab is 10
);

$attributeId = $setup->getAttributeId($category_entityTypeId, 'category_teorema');

$setup->run("
INSERT INTO `{$setup->getTable('catalog_category_entity_int')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$category_entityTypeId}', '{$attributeId}', `entity_id`, '1'
        FROM `{$setup->getTable('catalog_category_entity')}`;
");

Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(false)
    ->save();

//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(false)
    ->save();

$setup->endSetup();

$installer->endSetup();
