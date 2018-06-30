<?php 

class Best4Mage_ConfigurableProductsSimplePrices_Block_Product_Price extends Mage_Catalog_Block_Product_Price {

	public function _toHtml() {
		$html = trim(parent::_toHtml());

		if(preg_match('/tier/i', $this->getTemplate())) { 
			return $html;
		}

		$_product = $this->getProduct();
		//$_loadedProduct = Mage::getModel('catalog/product')->load($_product->getId());
		$_cpspHelper = Mage::helper('configurableproductssimpleprices');
		$_isEnable = $_cpspHelper->isEnable($_product); 
        $_showLowestPrice = $_cpspHelper->isShowLowestPrice($_product); 
        $_showMaxRegularPrice = $_cpspHelper->isShowMaxRegularPrice($_product); 
        $_showPriceRange = $_cpspHelper->isShowPriceRange();  

		if(empty($html) || ($_product->getTypeId() != 'configurable') || !$_isEnable || (!$_showLowestPrice && !$_showMaxRegularPrice && !$_showPriceRange)) { 
			return $html;
		}

		if($_showPriceRange && !$_cpspHelper->isSameMinMax($_product)) { 

			$minHtmlObject = new Varien_Object();
	        $minHtmlTemplate = $this->getLayout()->createBlock('core/template')
	            ->setTemplate('configurableproductssimpleprices/min-price.phtml')
	            ->setProduct($this->getProduct())
				->setIdSuffix('-range-from')
				->setShowRange(true)
	            ->toHtml();
	        $minHtmlObject->setHtml($minHtmlTemplate);
	        $minHtml = $html;
	        if(in_array(Mage::app()->getRequest()->getControllerName(), array('category','result', 'advanced', 'product_compare','index'))) {
	        	$minHtml = $minHtmlObject->getHtml();
	        } else {
	       		$minHtml .= $minHtmlObject->getHtml();
	       	}
	       	$minHtml .= $minHtmlObject->getSuffix();

	       	$maxHtmlObject = new Varien_Object();
	       	$maxHtmlTemplate = $this->getLayout()->createBlock('core/template')
                ->setTemplate('configurableproductssimpleprices/max-regular-price.phtml')
                ->setProduct($this->getProduct())
				->setIdSuffix('-range-to')
				->setShowRange(true)
                ->toHtml();
            $maxHtmlObject->setHtml($maxHtmlTemplate);
			$maxHtml = $maxHtmlObject->getPrefix();
        	$maxHtml .= $maxHtmlObject->getHtml();
        	$maxHtml .= $maxHtmlObject->getSuffix();

        	return $minHtml.$maxHtml;
	    }
		
        $htmlObject = new Varien_Object();  
		if($_showMaxRegularPrice) {  
            $htmlTemplate = $this->getLayout()->createBlock('core/template')
                ->setTemplate('configurableproductssimpleprices/max-regular-price.phtml')
                ->setProduct($this->getProduct())
				->setIdSuffix($this->getIdSuffix())
                ->toHtml();
            $htmlObject->setHtml($htmlTemplate);
			$html = $htmlObject->getPrefix();
        	$html .= $htmlObject->getHtml();
		}

		if(Mage::app()->getRequest()->getModuleName() == 'wishlist') {
			$minHtmlObject = new Varien_Object();
	        $minHtmlTemplate = $this->getLayout()->createBlock('core/template')
	            ->setTemplate('configurableproductssimpleprices/wishlist_price.phtml')
	            ->setProduct($this->getProduct())
				->setIdSuffix($this->getIdSuffix())
	            ->toHtml();
	        $minHtmlObject->setHtml($minHtmlTemplate);
	        if($_showMaxRegularPrice) { 
	        	$html .= $minHtmlObject->getHtml();
	        } else {
	        	$html = $minHtmlObject->getHtml();
	        }
	        $html .= $htmlObject->getSuffix();
			return $html;
		}

		if($_showLowestPrice) { 
			$minHtmlObject = new Varien_Object();
	        $minHtmlTemplate = $this->getLayout()->createBlock('core/template')
	            ->setTemplate('configurableproductssimpleprices/min-price.phtml')
	            ->setProduct($this->getProduct())
				->setIdSuffix($this->getIdSuffix())
	            ->toHtml();
	        $minHtmlObject->setHtml($minHtmlTemplate);
	        if(in_array(Mage::app()->getRequest()->getControllerName(), array('category', 'result', 'advanced', 'cart', 'index'))) {
	        	$html = $minHtmlObject->getHtml();
	        } else {
	       		$html .= $minHtmlObject->getHtml();
	       	}
	    }
	    $html .= $htmlObject->getSuffix();
		return $html;
	}
}