<?php
class Best4Mage_ConfigurableProductsSimplePrices_Block_Product_Options extends Mage_Catalog_Block_Product_View_Options {
	/**
     * Get json representation of
     *
     * @return string
     */
    public function getJsonConfig($useDefault = true)
    {
       /* if($useDefault){
            return parent::getJsonConfig();
        }*/
        $config = array();
        $_simProIds = array();
        $_confProOptions = parent::getOptions();
        $_confProduct = parent::getProduct();
        // $_simProIds = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($_confProduct->getId());

        // get custom options of configurable product
        foreach ($_confProOptions as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            $priceValue = 0;
            if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                $_tmpPriceValues = array();
                foreach ($option->getValues() as $value) {
                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
                    $id = $value->getId();
                    $_tmpPriceValues[$id] = $this->_getPriceConfiguration($value);
                }
                $priceValue = $_tmpPriceValues;
            } else {
                $priceValue = $this->_getPriceConfiguration($option);
            }
            $config[$option->getId()] = $priceValue;
        }

        $simpleProducts = Mage::helper('configurableproductssimpleprices')->getUsedProductCollection($_confProduct);

        // get custom options of all associated simple products
        // if(!empty($_simProIds)) {
        if(count($simpleProducts)) {
        	// foreach ($_simProIds[0] as $_simProId) {
            foreach ($simpleProducts as $_simProduct) { 
        		// $_simProduct = Mage::getModel('catalog/product')->load($_simProId);
        		if($_simProOptions = $_simProduct->getOptions()) {
        			foreach ($_simProOptions as $option) {
        				/* @var $option Mage_Catalog_Model_Product_Option */
			            $priceValue = 0;
			            if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
			                $_tmpPriceValues = array();
			                foreach ($option->getValues() as $value) {
			                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
			                    $id = $value->getId();
			                    $_tmpPriceValues[$id] = $this->_getPriceConfiguration($value);
			                }
			                $priceValue = $_tmpPriceValues;
			            } else {
			                $priceValue = $this->_getPriceConfiguration($option);
			            }
			            $config[$option->getId()] = $priceValue;
        			}
        		}
        	}
        }

        return Mage::helper('core')->jsonEncode($config);
    }
}