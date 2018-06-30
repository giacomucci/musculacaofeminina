<?php

class Best4Mage_ConfigurableProductsSimplePrices_Model_System_Config_Source_Stocks extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
		return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Don’t show stock')),
			array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Show In stock/Out of stock')),
			array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Show number In stock'))
        );
	}
	
	public function toOptionArray()
    {
        return $this->getAllOptions();
    }

}
