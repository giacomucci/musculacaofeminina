<?php

class Best4Mage_ConfigurableProductsSimplePrices_Block_Product_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar {
	/**
     * Set collection to pager
     *
     * @param Varien_Data_Collection $collection
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function setCollection($collection)
    {
    	$customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
    	//$storeId = Mage::app()->getStore()->getStoreId();
    	$websiteId = Mage::app()->getWebsite()->getId(); 

        $collection->getSelect()->columns(
            array(
                'simple_price' => new Zend_Db_Expr('(IF(e.type_id = "configurable",(SELECT IF( cpip.tier_price IS NOT NULL, LEAST(MIN(cpip.min_price),MIN(cpip.tier_price)),  MIN(cpip.min_price)) AS cpsp_price FROM catalog_product_index_price AS cpip WHERE cpip.entity_id IN(SELECT cpsl.product_id FROM catalog_product_super_link AS cpsl WHERE cpsl.parent_id = `e`.entity_id) AND cpip.website_id = '.$websiteId.'  AND cpip.customer_group_id = '.$customerGroupId.'),`price_index`.`min_price`))')
            ));

        $this->addOrderToAvailableOrders('simple_price','Price From');

        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }
        if ($this->getCurrentOrder() == 'simple_price') {
            $this->_collection->getSelect()->order('simple_price '.$this->getCurrentDirection());
        } else if($this->getCurrentOrder()) {
            $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }
        
        // echo $this->_collection->getSelect();
        return $this;
    }
}