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
 * Warehouse Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_Inventory
 * @author      Magestore Developer
 */
class Magestore_Inventoryplus_Block_Adminhtml_Warehouse_Edit_Tab_Certaintime extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare tab form's information
     *
     * @return Magestore_Inventory_Block_Adminhtml_Inventory_Edit_Tab_Form
     */
    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getWarehouseData()) {
            $data = Mage::getSingleton('adminhtml/session')->getWarehouseData();
            Mage::getSingleton('adminhtml/session')->setWarehouseData(null);
        } elseif (Mage::registry('warehouse_data')) {
            $data = Mage::registry('warehouse_data')->getData();
        }
        $fieldset3 = $form->addFieldset('other_email_form', array(
            'legend' => Mage::helper('inventoryplus')->__('Other email receive notify')
        ));
        $fieldset1 = $form->addFieldset('text_message_form', array(
            'legend' => Mage::helper('inventoryplus')->__('Email')
        ));
        $fieldset2 = $form->addFieldset('certain_time_form', array(
            'legend' => Mage::helper('inventoryplus')->__('Order Status Monitoring')
        ));
        $adminId = Mage::getSingleton('admin/session')->getUser()->getId();
        $admin = Mage::getModel('admin/user')->load($adminId);
        $roleId = $admin->getRole()->getId();
        $allowAll = false;
        $adminRule = Mage::getModel('admin/rules')->getCollection()
                ->addFieldToFilter('role_id',$roleId)
                ->addFieldToFilter('resource_id','all')
                ->setPageSize(1)
                ->setCurPage(1)
                ->getFirstItem();
        if($adminRule->getPermission() == 'allow'){
            $allowAll = true;
        }
        
        $current_username = $admin->getUsername();
        $warehouseManager = isset($data['manager']) ? $data['manager'] : $admin->getUsername();
        $isManager = false;
        if ($current_username == $warehouseManager) {
            $isManager = true;
        }
        $users = Mage::getModel('admin/user')->getCollection();
        $adminArray = array();
        foreach ($users as $user) {
            $adminArray[$user->getUsername()] = $user->getFirstname() .' '. $user->getLastname() . ' ('. $user->getEmail().') ';
        }

        $disabled = true;
        if ($isManager || !$this->getRequest()->getParam('id')) {
            $disabled = false;
        }
        
        if (isset($data['warehouse_id']) && !$isManager) {
            $readonly = !Mage::helper('inventoryplus/warehouse')->canEdit($adminId, $data['warehouse_id']);
        } else {
            $readonly = false;
        }
        
        $orderStatus = Mage::getModel('sales/order_status')->getCollection();
        foreach ($orderStatus as $item_status){
            $fieldset2->addField('orderstatus_'.$item_status->getStatus(), 'text', array(
                'label' => $item_status->getLabel(),
                'name' => 'orderstatus_'.$item_status->getStatus(),
                'disabled' => $readonly,
            ));
        }

        $fieldset3->addField('other_email', 'textarea', array(
            'label' => Mage::helper('inventoryplus')->__('List other email receive notify'),
            'name' => 'other_email',
            'disabled' => $readonly,
        ));

        $fieldset1->addField('text_message', 'textarea', array(
            'label' => Mage::helper('inventoryplus')->__('Message send notify'),
            'name' => 'text_message',
            'disabled' => $readonly,
        ));

        if(isset($data['certain_time'])){
            $orderStatusData = (array) json_decode($data['certain_time']);
            foreach ($orderStatusData as $key => $dataItem){
                $data[$key] = $dataItem;
            }
        }
        $form->setValues($data);
        Mage::dispatchEvent('warehouse_edit_tab_form2',array('block' => $this));
        return parent::_prepareForm();
    }

}
