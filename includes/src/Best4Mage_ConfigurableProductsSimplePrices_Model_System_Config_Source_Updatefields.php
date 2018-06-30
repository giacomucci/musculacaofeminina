<?php

class Best4Mage_ConfigurableProductsSimplePrices_Model_System_Config_Source_Updatefields extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label'=>Mage::helper('adminhtml')->__('No Selection')),
			array('value' => 'name', 'label'=>Mage::helper('adminhtml')->__('Title')),
            array('value' => 'short_description', 'label'=>Mage::helper('adminhtml')->__('Short Description')),
            array('value' => 'description', 'label'=>Mage::helper('adminhtml')->__('Long Description')),
            array('value' => 'attributes', 'label'=>Mage::helper('adminhtml')->__('Additional Information')),
			array('value' => 'image', 'label'=>Mage::helper('adminhtml')->__('Image')),
            array('value' => 'sku', 'label'=>Mage::helper('adminhtml')->__('SKU')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
		$toOptionArray = $this->toOptionArray();
		$toArray = array();
		foreach($toOptionArray as $toOptionVal){
			$toArray[$toOptionVal['value']] = $toOptionVal['label'];
		}
        return $toArray;
    }


    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->toOptionArray();
        }
        return $this->_options;
    }

}
