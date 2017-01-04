<?php 
$installer = $this;
$installer->startSetup();
$resource = Mage::getModel('core/resource');

$installer->run("
     ALTER TABLE {$resource->getTableName('inventoryplus/warehouse')} 
         ADD `text_message` varchar(255) NOT NULL DEFAULT ''; 
");

$installer->endSetup();