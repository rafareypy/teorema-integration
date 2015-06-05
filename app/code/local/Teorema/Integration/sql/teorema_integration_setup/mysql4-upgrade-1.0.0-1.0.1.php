<?php

$installer = $this;


//adicionado codigo de categoria no Magento referenciando o Teorema
$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');

$setup->startSetup();

$category_entityTypeId     = $setup->getEntityTypeId('catalog_category');
$category_attributeSetId   = $setup->getDefaultAttributeSetId($category_entityTypeId);
$category_attributeGroupId = $setup->getDefaultAttributeGroupId($category_entityTypeId, $category_attributeSetId);

$setup->addAttribute('catalog_category', 'code_teorema',  array(
    'type'     => 'text',
    'label'    => 'Codigo Categoria teorema ',
    'group'    => 'Global',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => 0,
    'source' => 'eav/entity_attribute_source_boolean',
));

$setup->addAttributeToGroup(
    $category_entityTypeId,
    $category_attributeSetId,
    $category_attributeGroupId,
    'code_teorema',
    '12'					//last Magento's attribute position in General tab is 10
);

$attributeId = $setup->getAttributeId($category_entityTypeId, 'code_teorema');

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


//------------------------------------------------------------------------------

//Criando codigo tipo de categoria magento referenciando teorema

$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');

$setup->startSetup();

$category_entityTypeId     = $setup->getEntityTypeId('catalog_category');
$category_attributeSetId   = $setup->getDefaultAttributeSetId($category_entityTypeId);
$category_attributeGroupId = $setup->getDefaultAttributeGroupId($category_entityTypeId, $category_attributeSetId);

$setup->addAttribute('catalog_category', 'type_teorema',  array(
    'type'     => 'text',
    'label'    => 'Tipo Categoria teorema ',
    'input'    => 'text',
    'group'    => 'Global',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => 0,
    'source' => 'eav/entity_attribute_source_boolean',
));


$setup->addAttributeToGroup(
    $category_entityTypeId,
    $category_attributeSetId,
    $category_attributeGroupId,
    'type_teorema',
    '13'					//last Magento's attribute position in General tab is 10
);

$attributeId = $setup->getAttributeId($category_entityTypeId, 'type_teorema');

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
