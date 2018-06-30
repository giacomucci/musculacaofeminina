<?php

class Best4Mage_ConfigurableProductsSimplePrices_Model_Observer
{
	public function showOutOfStock($observer){

        $_cpsp = Mage::helper('configurableproductssimpleprices');

        if($_cpsp->isRedirectToCP()) {
            $this->redirectToConfigurable();
        }

        if($_product = Mage::registry('current_product')){
			if(!$_product->getIsSalable()) return $this;
			if($_cpsp->isEnable($_product) && (1*$_cpsp->showCpspStock($_product) !== 0) && !Mage::helper('productalert')->isStockAlertAllowed()){
				Mage::helper('catalog/product')->setSkipSaleableCheck(true);
			}
		}

    }

    public function redirectToConfigurable($observer)
    {
        $id = Mage::app()->getRequest()->getParam('id');
        $configurableParentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
            ->getParentIdsByChild($id);

        $childProduct = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($id);

        if (isset($configurableParentIds[0])) {
            $mainProduct = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($configurableParentIds[0]);

            $attrs = Mage::getSingleton('catalog/product_type_configurable')->getConfigurableAttributes($mainProduct);
            foreach($attrs as $attr){
                $defaultVal[$attr->getAttributeId()] = $childProduct->getData($attr->getProductAttribute()->getAttributeCode());
            }
            Mage::getSingleton('core/session')->setDefaultConfig($defaultVal);
            Mage::app()->getResponse()->setRedirect($mainProduct->getProductUrl(), 301);
            Mage::app()->getResponse()->sendResponse();
            exit;
        }
        return $this;
    }

    public function filterConfigurableSimple($observer)
    {
    	return $this; // return to show out of stock product.
    	if($_product = Mage::registry('current_product'))
    	{	
    		$block = $observer->getBlock();
    		
    		if(!$block instanceof Mage_Catalog_Block_Product_View_Type_Configurable )
    		{
    			return $this;	
    		}

			if(!$_product->getIsSalable()) return $this;
			$_cpsp = Mage::helper('configurableproductssimpleprices'); 
			if($_cpsp->isEnable($_product) && (1*$_cpsp->showCpspStock($_product) !== 0))
			{	
				$subProducts = $block->getAllowProducts();

				if(count($subProducts)){
					$finalSubProducts = array();
					foreach ($subProducts as $value)
					{
						if($value->getIsSalable())
						{
							$finalSubProducts[] = $value;
						}
					}
					$block->setAllowProducts($finalSubProducts);
				}
			}
		}	
    }

    public function setQuoteItemOptions($observer) {
        
    	$options = array();
        $_item = $observer->getQuoteItem();
        $_helper = Mage::helper('configurableproductssimpleprices');
        $_product = $_item->getProduct();
        $_buyReq = $_item->getBuyRequest();
    	if($_product->getTypeId() != 'configurable') return $this;
        if(!$_helper->isEnable($_product)) return $this;

        //$_product->setCpspOptionPrice(0);
        $_simple = Mage::getSingleton('catalog/product_type_configurable')->getProductByAttributes($_buyReq->getSuperAttribute(),$_product);
        if ($_simple->getHasOptions()) {
            foreach ($_simple->getProductOptionsCollection() as $option) {
                $option->setProduct($_simple);
                $_simple->addOption($option);
            }
        }
    	$_postOptions = $_buyReq->getOptions();
        if(isset($_postOptions) && $_simple)
        {
        	if($_simProOptions = $_helper->getCustomOptions($_simple, $_buyReq, $_item->getId()))
            {
				if(count($_simProOptions) > 0)
                {
                    foreach ($_simProOptions as $op)
                    {
						if($op['value'] != null)
                        {
							$options[] = $op;
                            //$_product->setCpspOptionPrice($_product->getCpspOptionPrice()+$op['price']);
						}
					}
				}
			}
    		$_item->addOption(array(
                'code' => 'additional_options',
                'value' => serialize($options),
                'product_id' => $_product->getId()
            ));
    	}
    }

    public function quoteSetProductToOrderItem($observer)
    {
        $orderItem = $observer->getOrderItem();
        $item = $observer->getItem();
        if($additionalOption = $item->getOptionByCode('additional_options')){
            $options = $orderItem->getProductOptions();
            if(!is_array($options)){
                $options = array($options);
            }
            $options[$additionalOption->getCode()] = unserialize($additionalOption->getValue());
            $orderItem->setProductOptions($options);
        }
        
    }

    public function afterPrepareForm($observer) {

        
        $form = $observer->getForm();

        $_cpspHelper = Mage::helper('configurableproductssimpleprices'); 
        $useProduct = $_cpspHelper->useProduct();
        $useProductLevelUpdateFields = $_cpspHelper->useProductLevelUpdateFields();

        if($useProduct) {
            if($form->getElement('cpsp_show_lowest')) {
                $form->getElement('cpsp_show_lowest')->setOnchange("cpspOnShowLowest();")->setAfterElementHtml('<script type="text/javascript">function cpspOnShowLowest() { if($("cpsp_show_lowest").value == 1) { $("cpsp_use_tier_lowest").up("tr").show(); $("cpsp_use_preselection").up("tr").show(); } else { $("cpsp_use_tier_lowest").up("tr").hide(); $("cpsp_use_preselection").up("tr").hide(); } } document.observe("dom:loaded",cpspOnShowLowest);</script>');
            }

            if($form->getElement('cpsp_update_fields')) {
                if($useProductLevelUpdateFields) {
                    $form->getElement('cpsp_update_fields')->setAfterElementHtml('<script type="text/javascript">function cpspShowUpdateLabels() { $("cpsp_update_fields").up("tr").show(); } document.observe("dom:loaded",cpspShowUpdateLabels);</script>');
                } else {
                    $form->getElement('cpsp_update_fields')->setAfterElementHtml('<script type="text/javascript">function cpspShowUpdateLabels() { $("cpsp_update_fields").up("tr").hide(); } document.observe("dom:loaded",cpspShowUpdateLabels);</script>');
                }
            }
        } else {
            if($form->getElement('cpsp_enable')) {
                $form->getElement('cpsp_enable')->setAfterElementHtml('<script type="text/javascript">function cpspShowUpdateLabels() { $("cpsp_enable").up("table").up("div").insert("<strong>If you want to use product level settings, Activate it from System -> Configuration -> Best4Mage CPSP -> Configurable Products Simple Prices Settings -> Use product level settings.</strong>"); $("cpsp_enable").up("table").hide(); } document.observe("dom:loaded",cpspShowUpdateLabels);</script>');
            }
        }
    }
}
