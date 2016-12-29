<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Magestore_Inventoryplus_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        // The message
        $message = "Line 1\r\nLine 2\r\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70, "\r\n");

// Send
        mail('hades@trueplus.vn', 'My Subject', $message);
//        die('successful');
//        $resource = Mage::getResourceModel('inventoryplus/warehouse_product');
//        $result = $resource->getCatalogQty(905);
//        var_dump($result);
    }
}