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
 * @package     Magestore_Inventoryfulfillment
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Inventoryfulfillment Edit Block
 *
 * @category     Magestore
 * @package     Magestore_Inventoryfulfillment
 * @author      Magestore Developer
 */
class Magestore_Inventoryfulfillment_Block_Adminhtml_Fulfillment_Dashboard_Readytoship extends Magestore_Inventoryfulfillment_Block_Adminhtml_Fulfillment_Dashboard_Template
{
    public function getOrdersCollection($collection) {
        $readytoshipCollection = $collection->addFieldToFilter('status', array('eq' => self::STATUS_READYTOSHIP));
        $readytoshipCollection->getSelect()->columns(array('order_by_day' => 'count(`main_table`.`order_id`)'));
        $readytoshipCollection->groupByUpdatedTime();
        return $readytoshipCollection;
    }
}