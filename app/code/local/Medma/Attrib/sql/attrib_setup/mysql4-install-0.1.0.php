<?php
$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('catalog_category', 'start_date',  array(
    'type'     => 'datetime',
    'label'    => 'Start Date',
    'input'    => 'date',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => 0
));

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'start_date',
    '11'

//last Magento's attribute position in General tab is 10
);

$attributeId = $installer->getAttributeId($entityTypeId, 'start_date');

$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_int')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, '1'
        FROM `{$installer->getTable('catalog_category_entity')}`;
");

/***********************add second attribute*******************/
$installer->addAttribute('catalog_category', 'display_date',  array(
    'type'     => 'datetime',
    'label'    => 'Expiry Date',
    'input'    => 'date',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => 0
));
 
$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'display_date',
    '12'                    //last Magento's attribute position in General tab is 10
);
 
$attributeId = $installer->getAttributeId($entityTypeId, 'display_date');
 
$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_int')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, '1'
        FROM `{$installer->getTable('catalog_category_entity')}`;
");
/***********************add second attribute********************/
//this will set data of your custom attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

$installer->endSetup();
?>
