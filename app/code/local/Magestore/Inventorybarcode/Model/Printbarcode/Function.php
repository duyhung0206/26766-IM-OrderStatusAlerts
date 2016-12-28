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
 * @package     Magestore_Inventorybarcode
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Inventorybarcode Display Model
 * 
 * @category    Magestore
 * @package     Magestore_Inventorybarcode
 * @author      Magestore Developer
 */
class Magestore_Inventorybarcode_Model_Printbarcode_Function extends Varien_Object {

    /**
     * Image keys
     *
     * @var array
     */
    protected $imageKeys = array();
    
     
    public function registerImageKey($key, $value) {
        global $imageKeys;
        $imageKeys[$key] = $value;
    }

    public function getImageKeys() {
        global $imageKeys;
        return $imageKeys;
    }

    public function getElementHtml($tag, $attributes, $content = false) {
        $code = '<' . $tag;
        foreach ($attributes as $attribute => $value) {
            $code .= ' ' . $attribute . '="' . htmlentities(Mage::helper('inventoryplus')->stripSlashes($value), ENT_COMPAT) . '"';
        }

        if ($content === false || $content === null) {
            $code .= ' />';
        } else {
            $code .= '>' . $content . '</' . $tag . '>';
        }

        return $code;
    }

    public function getInputTextHtml($name, $currentValue, $attributes = array()) {
        $defaultAttributes = array(
            'id' => $name,
            'name' => $name
        );

        $finalAttributes = array_merge($defaultAttributes, $attributes);
        if ($currentValue !== null) {
            $finalAttributes['value'] = $currentValue;
        }

        return $this->getElementHtml('input', $finalAttributes, false);
    }

    public function getOptionGroup($options, $currentValue) {
        $content = '';
        foreach ($options as $optionKey => $optionValue) {
            if (is_array($optionValue)) {
                $content .= '<optgroup label="' . $optionKey . '">' . getOptionGroup($optionValue, $currentValue) . '</optgroup>';
            } else {
                $optionAttributes = array();
                if ($currentValue == $optionKey) {
                    $optionAttributes['selected'] = 'selected';
                }
                $content .= $this->getOptionHtml($optionKey, $optionValue, $optionAttributes);
            }
        }

        return $content;
    }

    public function getOptionHtml($value, $content, $attributes = array()) {
        $defaultAttributes = array(
            'value' => $value
        );

        $finalAttributes = array_merge($defaultAttributes, $attributes);

        return $this->getElementHtml('option', $finalAttributes, $content);
    }

    public function getSelectHtml($name, $currentValue, $options, $attributes = array()) {
        $defaultAttributes = array(
            'size' => 1,
            'id' => $name,
            'name' => $name
        );

        $finalAttributes = array_merge($defaultAttributes, $attributes);
        $content = $this->getOptionGroup($options, $currentValue);

        return $this->getElementHtml('select', $finalAttributes, $content);
    }

    public function getCheckboxHtml($name, $currentValue, $attributes = array()) {
        $defaultAttributes = array(
            'type' => 'checkbox',
            'id' => $name,
            'name' => $name,
            'value' => isset($attributes['value']) ? $attributes['value'] : 'On'
        );

        $finalAttributes = array_merge($defaultAttributes, $attributes);
        if ($currentValue == $finalAttributes['value']) {
            $finalAttributes['checked'] = 'checked';
        }

        return $this->getElementHtml('input', $finalAttributes, false);
    }

    public function getButton($value, $output = null) {
        $escaped = false;
        $finalValue = $value[0] === '&' ? $value : htmlentities($value);
        if ($output === null) {
            $output = $value;
        } else {
            $escaped = true;
        }

        $code = '<input type="button" value="' . $finalValue . '" data-output="' . $output . '"' . ($escaped ? ' data-escaped="true"' : '') . ' />';
        return $code;
    }

    /**
     * Returns the fonts available for drawing.
     *
     * @return string[]
     */
    public function listfonts($folder) {
        $array = array();
        if (($handle = Mage::helper('inventoryplus')->openDir($folder)) !== false) {
            while (($file = readdir($handle)) !== false) {
                if (substr($file, -4, 4) === '.ttf') {
                    $array[$file] = $file;
                }
            }
        }
        closedir($handle);

        array_unshift($array, 'No Label');

        return $array;
    }

    /**
     * Returns the barcodes present for drawing.
     *
     * @return string[]
     */
    public function listbarcodes() {
        

        $availableBarcodes = Mage::helper('inventorybarcode/print')->getBarcodeList();
        foreach ($supportedBarcodes as $file => $title) {
            if (Mage::helper('inventoryplus')->fileExists($file)) {
                $availableBarcodes[$file] = $title;
            }
        }

        return $availableBarcodes;
    }

    public function findValueFromKey($haystack, $needle) {
        foreach ($haystack as $key => $value) {
            if (strcasecmp($key, $needle) === 0) {
                return $value;
            }
        }

        return null;
    }

    public function convertText($text) {
        $text = Mage::helper('inventoryplus')->stripSlashes($text);
        if (function_exists('mb_convert_encoding')) {
            $text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
        }

        return $text;
    }

}
