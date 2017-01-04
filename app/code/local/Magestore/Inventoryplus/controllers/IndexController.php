<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Magestore_Inventoryplus_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $template_id   = Mage::getStoreConfig('inventoryplus/general/order_status_alert');
        $storeId       = Mage::app()->getStore()->getStoreId();
        $emailTemplate = Mage::getModel('core/email_template')->load($template_id);

        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));

        $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));

        $warehouses = Mage::getModel('inventoryplus/warehouse')->getCollection();
        foreach ($warehouses as $warehouse){
            $certain_times = (array)json_decode($warehouse->getData('certain_time'));
            $other_email = explode(';', $warehouse->getData('other_email'));
            $manager_email = $warehouse->getData('manager_email');
            $manager_name = $warehouse->getData('manager_name');
            $text_message = $warehouse->getData('text_message');
            $arrayOrder = array();
            $timestamp =  Mage::getModel('core/date')->timestamp(strtotime(time()));
            if(count($certain_times) > 0 && count($other_email) > 0){
                foreach ($certain_times as $key => $certain_time){
                    $orderstatus = substr($key, 12);
                    $time_config = $certain_time;
                    $orders = Mage::getModel('sales/order')->getCollection();
                    $status = Mage::getModel('sales/order_status')->getCollection()
                        ->addFieldToFilter('status', $orderstatus)->getFirstItem()->getData('label');
                    $arrayOrder[$status] = array();
                    foreach ($orders as $order){
                        if($order->getStatus() == $orderstatus){
                            if(($order->getCreatedAt() + $certain_time*24*60*60) < $timestamp){
                                $arrayOrder[$status][] = '#'.$order->getIncrementId();
                            }
                        }
                    }
                }
            }else{
                break;
            }
            if(count($arrayOrder) == 0){
                break;
            }else{
                $table = "<table style='width: 100%'><thead>";
                $th = "";
                $td = "";
                $tr = "";

                foreach ($arrayOrder as $keyStatus => $itemStatus){
                    $th .= "<th style='background-color: #e8503a;color: white;padding: 8px;'>$keyStatus</th>";
                    $dataTd = "";
                    $td = "";
                    foreach ($itemStatus as $item){
                        $dataTd .= $item."<br/>";
                    }
                    $td = "<td style='padding-left: 5px;vertical-align: top;'>".$dataTd."</td>";
                    $tr .= $td;
                }
                $tr = "<tr style='border-bottom: 1px solid #000000'>".$tr."</tr>";
                $table .= $th."</thead><tbody>$tr</tbody></table>";
                $vars = array(
                    'data_order_status_alert' => $text_message."<br/>".$table,
                );
            }

            $emailTemplate->getProcessedTemplate($vars);

            $isCheck = false;
            foreach ($other_email as $o_email){
                if(trim($o_email) == $manager_email)
                    $isCheck = true;
                if (!filter_var(trim($o_email), FILTER_VALIDATE_EMAIL) == false) {
                    $emailTemplate->send(trim($o_email), 'Other name', $vars);
                }
            }

            if(!$isCheck){
                if (!filter_var($manager_email, FILTER_VALIDATE_EMAIL) == false) {
                    $emailTemplate->send($manager_email, $manager_name, $vars);
                }
            }

        }

    }
}