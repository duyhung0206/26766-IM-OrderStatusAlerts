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
 * Inventory Model
 * 
 * @category    Magestore
 * @package     Magestore_Inventory
 * @author      Magestore Developer
 */
class Magestore_Inventoryplus_Model_Warehouse_Order extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('inventoryplus/warehouse_order');
    }

    /**
     * Get original warehouse_id from order_id
     * 
     * @param int|string $orderId
     * @return string
     */
    public function getOriginalWarehouseId($orderId) {
        $item = $this->getCollection()->addFieldToFilter('order_id', $orderId)
                ->setOrder('warehouse_order_id', 'ASC')
                ->setPageSize(1)->setCurPage(1)
                ->getFirstItem();
        return $item->getWarehouseId();
    }

}
