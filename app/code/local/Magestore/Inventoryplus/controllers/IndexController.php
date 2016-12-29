<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Magestore_Inventoryplus_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {$template_id   = Mage::getStoreConfig('inventoryplus/general/order_status_alert');
        $storeId       = Mage::app()->getStore()->getStoreId();
        $emailTemplate = Mage::getModel('core/email_template')->load($template_id);
//        $vars          = array(
//            'customer_name' => 'test111111111'
//        );
//        $emailTemplate->getProcessedTemplate($vars);

        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));

        $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));
//        $emailTemplate->send($receiveEmail, $receiveName, $vars);

        $warehouses = Mage::getModel('inventoryplus/warehouse')->getCollection();
        foreach ($warehouses as $warehouse){
            $certain_times = (array)json_decode($warehouse->getData('certain_time'));
            $other_email = explode(';', $warehouse->getData('other_email'));
            $manager_email = $warehouse->getData('manager_email');
            $manager_name = $warehouse->getData('manager_name');
            $arrayOrder = array();
            $timestamp =  Mage::getModel('core/date')->timestamp(strtotime(time()));
            if(count($certain_times) > 0 && count($other_email) > 0){
                foreach ($certain_times as $key => $certain_time){
                    $orderstatus = substr($key, 12);
                    $time_config = $certain_time;
                    $orders = Mage::getModel('sales/order')->getCollection();
                    $arrayOrder[$orderstatus] = array();
                    foreach ($orders as $order){
                        if($order->getStatus() == $orderstatus){
                            if(($order->getCreatedAt() + $certain_time*24*60*60) < $timestamp){
                                $arrayOrder[$orderstatus][] = $order->getId();
                            }
                        }
                    }
                }
            }else{
                break;
            }
            $vars          = array(
                'data_order_status_alert' => Zend_Debug::dump($arrayOrder)
            );
            $emailTemplate->getProcessedTemplate($vars);

            $isCheck = false;
            foreach ($other_email as $o_email){
                if(trim($o_email) == $manager_email)
                    $isCheck = true;
                $emailTemplate->send($o_email, 'Other name', $vars);
            }

            if(!$isCheck){
                if (!filter_var($manager_email, FILTER_VALIDATE_EMAIL) === false) {
                    $emailTemplate->send($manager_email, $manager_name, $vars);
                }
            }



        }

    }
}