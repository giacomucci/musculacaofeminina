<?php

class Best4Mage_ConfigurableProductsSimplePrices_Block_Product_Attributes extends Mage_Catalog_Block_Product_View_Attributes
{
    protected $_product = null;

    function getProduct()
    {   
        if($this->hasData('product')) {
            $this->_product = $this->getData('product');
        }

        if (!$this->_product) {

            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }

}
