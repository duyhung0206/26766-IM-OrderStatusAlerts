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
 * Inventory Resource Model
 * 
 * @category    Magestore
 * @package     Magestore_Inventory
 * @author      Magestore Developer
 */
class Magestore_Inventoryplus_Model_Mysql4_Warehouse_Permission extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('inventoryplus/warehouse_permission', 'warehouse_permission_id');
    }

    /**
     * Get warehouse permission
     * 
     * @param int $adminId
     * @param int $warehouseId
     * @return array
     */
    public function getPermission($adminId, $warehouseId) 
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                ->from($this->getMainTable())
                ->where('warehouse_id = :warehouse_id')
                ->where('admin_id = :admin_id');
        $bind = array(':warehouse_id' => $warehouseId, ':admin_id' => $adminId);

        $query = $adapter->query($select, $bind);
        while ($row = $query->fetch()) {
            return $row;
        }
    }

}
