<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Inventory
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Inventory Resource Collection Model
 * 
 * @category    Magestore
 * @package     Magestore_Inventory
 * @author      Magestore Developer
 */
class Magestore_Inventoryplus_Model_Mysql4_Warehouse_Shipment_Collection
    extends Magestore_Inventoryplus_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('inventoryplus/warehouse_shipment');
    }

    /**
     * 
     * @param int $rowId
     * @return \Magestore_Inventoryplus_Model_Mysql4_Warehouse_Shipment_Collection
     */
    public function getWarehouseById($rowId)
    {
        $this->addFieldToFilter('order_id', $rowId)
        ->addFieldToFilter('qty_shipped', array('gt' => 0));
        $this->getSelect()->group('warehouse_id');
        return $this;
    }
    
    /**
     * 
     * @return \Magestore_Inventoryplus_Model_Mysql4_Warehouse_Shipment_Collection
     */
    public function groupByWarehouseId() {
        $this->getSelect()->group('warehouse_id');        
        return $this;
    }
}