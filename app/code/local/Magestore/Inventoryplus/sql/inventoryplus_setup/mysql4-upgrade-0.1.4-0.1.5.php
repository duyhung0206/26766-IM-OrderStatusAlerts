<?php 
$installer = $this;
$installer->startSetup();
$resource = Mage::getModel('core/resource');

$installer->run("
     ALTER TABLE {$resource->getTableName('inventoryplus/warehouse')} 
         ADD `certain_time` varchar(255) NOT NULL DEFAULT ''; 
     ALTER TABLE {$resource->getTableName('inventoryplus/warehouse')} 
         ADD `other_email` varchar(255) NOT NULL DEFAULT ''; 
");

$installer->endSetup();