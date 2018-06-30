<?php

class Best4Mage_ConfigurableProductsSimplePrices_Helper_Data extends Mage_Core_Helper_Abstract
{
	private $_store = null;

	protected $_usedProductCollection = array();

	public function getUsedProductCollection(Mage_Catalog_Model_Product $product) {

		if(!isset($this->_usedProductCollection[$product->getId()])) {
			$this->_usedProductCollection[$product->getId()] = $product->getTypeInstance(true)
						->getUsedProductCollection($product)
						->addAttributeToSelect('*');

			$backend = $this->_getMediaGalleryBackend();
			foreach ($this->_usedProductCollection[$product->getId()] as $child) {
				$backend->afterLoad($child->load($child->getId()));
			}
		}
		return $this->_usedProductCollection[$product->getId()];
	}


	protected static $_mediaGalleryBackend = null;

    protected function _getMediaGalleryBackend() {
        if (self::$_mediaGalleryBackend === null) {

            $mediaGallery = Mage::getSingleton('eav/config')
                ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_gallery');

            self::$_mediaGalleryBackend = $mediaGallery->getBackend();

        }

        return self::$_mediaGalleryBackend;
    }


	public function useProduct()
	{
		if($this->_store == null) $this->_store = Mage::app()->getStore();
		return $this->getConfig('product_level');
	}
	
	public function isEnable($product)
	{
		if($this->useProduct() == 1) {
			if(!$product->hasData('cpsp_enable')) {
				$product = Mage::getModel('catalog/product')->load($product->getId());
			}
			return ($product->getCpspEnable() == 1);
		} else return ($this->getConfig('enable') == 1);
	}

	protected $_productResource = null;

	public function getTaxClassId($pid)
	{
		if($this->_productResource == null){
			$this->_productResource = Mage::getSingleton('catalog/product')->getResource();
		}

		return $this->_productResource->getAttributeRawValue($pid,'tax_class_id', $this->_store);
	}
	
	public function isShowPrices($product)
	{
		if($this->useProduct() == 1) return ($product->getCpspExpandPrices() == 1);
		else return ($this->getConfig('expand_prices', 'dropdown') == 1);
	}

	public function isHideSameLowest($product)
	{
		if($this->getConfig('hide_same_lowest', 'price') == 1){
			//$simpleProducts = $product->getTypeInstance(true)->getUsedProductIds($product);
			$simpleProducts = $this->getUsedProductCollection($product);
			$simplePriceArray = array();
			if(count($simpleProducts) > 0){
				if(count($simpleProducts) == 1) return false;

				foreach ($simpleProducts as $simpleProduct) {
					//$simpleProduct = Mage::getModel('catalog/product')->load($simpleProduct);
					$simplePriceArray[] = $simpleProduct->getFinalPrice();
				}
				if(count($simplePriceArray) == 1)
				{
					return false;
				}
				elseif(count(array_unique($simplePriceArray)) != count($simplePriceArray) && count(array_unique($simplePriceArray)) == 1)
				{
					return false;
				}
			}
		}
		return true;
	}

	static $lowestPrice = null;

	public function isShowLowestPrice($product)
	{
		self::$lowestPrice = false;
		if($this->useProduct() == 1)
			self::$lowestPrice = ($product->getCpspShowLowest() == 1);
		else
			self::$lowestPrice = ($this->getConfig('show_lowest', 'price') == 1);
		
		if(self::$lowestPrice){
			self::$lowestPrice = $this->isHideSameLowest($product);
		}
		return self::$lowestPrice;
	}

	public function isUsePreselection($product) {
		if($this->isShowLowestPrice($product) == 1) {
			if($this->useProduct() == 1) {
				return ($product->getCpspUsePreselection() == 1);
			} else {
				return ($this->getConfig('use_preselection', 'price') == 1);
			}
		} else {
			return false;
		}
	}

	public function isUseTierLowest($product) {
		if($this->isShowLowestPrice($product) == 1) { 
			if($this->useProduct() == 1) {
				return ($product->getCpspUseTierLowest() == 1);
			} else {
				return ($this->getConfig('use_tier_lowest', 'price') == 1);
			}
		} else { 
			return false;
		}
	}

	public function isUseOutOfStockProduct() {
		if(self::$lowestPrice) { 
			return ($this->getConfig('use_out_of_stock_product', 'price') == 1);
		} else { 
			return false;
		}
	}
	
	public function isShowMaxRegularPrice($product)
	{
		if($this->useProduct() == 1) return ($product->getCpspShowMaxregular() == 1);
		else return ($this->getConfig('show_maxregular', 'price') == 1);
	}
	
	public function showCpspStock($product)
	{
		if($this->useProduct() == 1) return $product->getCpspShowStock();
		else return $this->getConfig('show_stock', 'dropdown');
	}
	
	public function isTierConfigurable($product)
	{
		if($this->useProduct() == 1){
			if(is_object($product)) {
				$productId = $product->getId();
			} else {
				$productId = $product;
			}
			if(!$product->hasData('cpsp_enable_tier')) {
				$product = Mage::getModel('catalog/product')->load($productId);
			}
			return ($product->getCpspEnableTier() == 1);
		} else return $this->getConfig('enable_tier', 'price');
	}

	public function showLastPrice($product)
	{
		if($this->useProduct() == 1) return ($product->getCpspShowLastPrice() == 1);
		else return $this->getConfig('show_last_price', 'price');
	}

	public function useProductLevelUpdateFields() {
		return $this->getConfig('update_fields_product_level', 'infoupdate');
	}
	
	public function getCpspUpdateFields($product){
		if($this->useProduct() == 1 && $this->useProductLevelUpdateFields() == 1) {
			$flds = explode(',', $product->getCpspUpdateFields());
		} else {
			$flds = explode(',', $this->getConfig('update_fields', 'infoupdate'));
		}
		return array_filter($flds);
	}
	
	public function isTierBase()
	{
		return ($this->getConfig('tier_base', 'settings', 'cptp') == 1);
	}
	
	public function getCpspPriceFormate()
	{
		return $this->generatedPriceFormatArray($this->getConfig('price_format', 'dropdown'));
	}
	
	public function isRemoveDecimalPoint()
	{
		return ($this->getConfig('choose_formate', 'dropdown') == 1);
	}

	public function isSwitchPriceVisibility()
	{
		return ($this->getConfig('switch_price_visibility', 'price') == 1);
	}

	public function isShowPriceRange()
	{
		return ($this->getConfig('show_price_range', 'price') == 1);
	}

	public function isRedirectToCP()
	{
		return ($this->getConfig('redirect_to_cp', 'redirect_options') == 1);
	}

	public function isChangeUrl()
	{
		return ($this->getConfig('change_url', 'redirect_options') == 1);
	}

	public function isSameMinMax($product) {

		list($spID, $_minimalPriceValue) = $this->getMinimalProductPrice($product->getId());
		list($maxSpId, $_maximumPriceValue) = $this->getMaximumProductPrice($product->getId());
		if($_minimalPriceValue === $_maximumPriceValue) {
			return true;
		}
		return false;
	}
	
	static $websiteId = null;
	static $storeId = null;
	static $groupId = null;
	static $statusId = null;
	static $taxClassId = null;
	static $minPriceArrey = array();
	static $maxPriceArrey = array();
	
	public function setUpStaticData($_product)
	{
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		
		if(self::$websiteId === null) self::$websiteId = Mage::app()->getWebsite()->getId();
		
		if(self::$storeId === null) self::$storeId = Mage::app()->getStore()->getStoreId();
		
		if(self::$groupId === null) self::$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
		
		if(self::$statusId === null) {
			self::$statusId = $readConnection->fetchOne("SELECT `attribute_id` FROM ".$resource->getTableName('eav_attribute')." WHERE `attribute_code` LIKE 'status' AND `entity_type_id` = ".$_product->getEntityTypeId());
		}
		
		if(self::$taxClassId === null) {
			self::$taxClassId = $readConnection->fetchOne("SELECT `attribute_id` FROM ".$resource->getTableName('eav_attribute')." WHERE `attribute_code` LIKE 'tax_class_id' AND `entity_type_id` = ".$_product->getEntityTypeId());
		}
	}
	
	public function getMinimalProductPrice($_productId)
	{
		$this->getProductPrices($_productId);
		$minPriceArrey = self::$minPriceArrey[$_productId];
		return array(array_keys($minPriceArrey, min($minPriceArrey)),min($minPriceArrey));
	}
	
	public function getMaximumProductPrice($_productId)
	{
		$this->getProductPrices($_productId);
		$maxPriceArrey = self::$maxPriceArrey[$_productId];
		return array(array_keys($maxPriceArrey, max($maxPriceArrey)),max($maxPriceArrey));
	}
	
	protected function getProductPrices($productId)
	{
		if(!array_key_exists($productId, self::$minPriceArrey) || !array_key_exists($productId, self::$maxPriceArrey)) {	

			$confProduct = Mage::getModel('catalog/product')->load($productId);
			//$assocProductIds = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($productId);
			$assocProducts = $this->getUsedProductCollection($confProduct);

			//foreach ($assocProductIds[0] as $id) { 
			foreach ($assocProducts as $child) { 
				$id = $child->getId();
				//$child = Mage::getModel('catalog/product')->load($id);
				//$child->setCustomerGroupId(self::$groupId);
				if((!$child->isSalable() || $child->getIsInStock()==0) && !$this->isUseOutOfStockProduct()) continue;
				
				$finalPrice = $child->getFinalPrice();
				$tierPrices = $child->getTierPrice();
				$finalMinTierPrice = null;
				$finalMaxTierPrice = null;

				if($this->isUseTierLowest($confProduct) && count($tierPrices)) {
					foreach ($tierPrices as $key => $tier) {
						if($finalMinTierPrice == null || $finalMinTierPrice > $tier['website_price']) {
							$finalMinTierPrice = $tier['website_price'];
						}
						if($finalMaxTierPrice == null || $finalMaxTierPrice < $tier['website_price']) {
							$finalMaxTierPrice = $tier['website_price'];
						}
					}
				}
				$minPrice = $finalMinTierPrice ? min(array($finalPrice,$finalMinTierPrice)) : $finalPrice;
				self::$minPriceArrey[$productId][$id] = $minPrice;
			
				$maxPrice = $finalMaxTierPrice ? max(array($finalPrice,$finalMaxTierPrice)) : $finalPrice;
				self::$maxPriceArrey[$productId][$id] = $maxPrice;
			}

			if($this->isTierConfigurable($confProduct)) {
				$parentTier = $confProduct->getTierPrice();
				$finalMinTierPrice = null;
				if(count($parentTier)) {
					foreach ($parentTier as $key => $tier) {
						if($finalMinTierPrice == null || $finalMinTierPrice > $tier['website_price']) {
							$finalMinTierPrice = $tier['website_price'];
						}
					}
				}
				if($finalMinTierPrice)
					self::$minPriceArrey[$productId][$productId] = $finalMinTierPrice;
			}
		}
	}

	private function getconfig($field, $group = 'settings', $tab = 'cpsp')
	{
		return Mage::getStoreConfig($tab.'/'.$group.'/'.$field, $this->_store);
	}
	
	public function getWishlistItemByProduct($product)
	{
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($customer->getId())
		{
			$wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customer, true);
			$wishListItemCollection = $wishlist->getItemCollection();
			foreach ($wishListItemCollection as $item)
			{
				if($item->representProduct($product))
				{
					return $item;	
				}
			}
		} else {
			return false;	
		}
	}
	
	private function generatedPriceFormatArray($key = 0)
	{
		if($key == null) $key = 0;
		$precision = 2;
		if($this->isRemoveDecimalPoint()) $precision = 0;
		$arrOfPriceFormat = array(
			'0' => array(
				'precision' => $precision,
				'requiredPrecision' => $precision,
				'decimalSymbol' => '.',
				'groupSymbol' => ',',
				'groupLength' => 3	
			),
			'1' => array(
				'precision' => $precision,
				'requiredPrecision' => $precision,
				'decimalSymbol' => ',',
				'groupSymbol' => '',
				'groupLength' => 3
			),
			'2' => array(
				'precision' => $precision,
				'requiredPrecision' => $precision,
				'decimalSymbol' => '.',
				'groupSymbol' => '',
				'groupLength' => 3
			)
		);
		
		return (array_key_exists($key,$arrOfPriceFormat) ? $arrOfPriceFormat[$key] : $arrOfPriceFormat[0]);
	}

	protected $_simpleSustomOptions = array();
	public function getCustomOptions(Mage_Catalog_Model_Product $product, $itemInfo, $itemId)
    {
		$usedKey = 'simple_custom_option_'.$itemId;

		if(!isset($this->_simpleSustomOptions[$usedKey]))
		{
			$options = array();
			foreach ($product->getOptions() as $option)
			{
			    /* @var $option Mage_Catalog_Model_Product_Option */
			    $group = $option->groupFactory($option->getType())
			        ->setOption($option)
			        ->setProduct($product)
			        ->setRequest($itemInfo)
			        ->validateUserValue($itemInfo->getOptions());

			    $optionValue = $itemInfo->getData('options');
			    $optionValue = $optionValue[$option->getId()];

			    $price = 0;
			    
	            if( !in_array( $option->getType(), array('drop_down','radio','checkbox','multiple') ) ) {
					$price += $option->getPrice();
				} else {
					foreach ($option->getValues() as $key => $value) {
		            	if(is_array($optionValue)){
		            		if(in_array($value->getId(), $optionValue))
		            		{
			                    $price += $value->getPrice(true);
			                }
		            	} elseif($value->getId() == $optionValue){
		                	$price += $value->getPrice(true);
		                }
		            }
				}


	            $optionValue = (is_array($optionValue) ? implode(',',$optionValue) : $optionValue);
			    $options[] = array(
			        'label' => $option->getTitle(),
			        'value' => $group->getFormattedOptionValue($optionValue),
			        'print_value' => $group->getPrintableOptionValue($optionValue),
			        'option_id' => $option->getId(),
			        'option_type' => $option->getType(),
			        'price' => $price,
			    );
			}
			$this->_simpleSustomOptions[$usedKey] = $options;
		}
		
		return $this->_simpleSustomOptions[$usedKey];
    }

    public function getItemBuyRequest($_itemId) {

    	$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		if($_itemId) {
			$query = "SELECT value
FROM ".$resource->getTableName("sales_flat_quote_item_option")."
WHERE `code` = 'info_buyRequest'
AND `item_id` = ".$_itemId;
			
			$_buyReq = $readConnection->fetchOne($query);
			return $_buyReq;
		}
    }

    public function addOptionsJs() {

    	$patchList = $this->getPatchList();
    	if(version_compare(Mage::getVersion(), '1.9.3', '>=') || in_array('SUPEE-8788', $patchList)) {
    		
    		return 'varien/product_options.js';
    	} else {

    		return '';
    	}
    }

    public function removeOptionsJs() {

    	$patchList = $this->getPatchList();
    	if(version_compare(Mage::getVersion(), '1.9.3', '>=') || in_array('SUPEE-8788', $patchList)) {

    		return 'js/varien/product_options.js';
    	} else {

    		return '';
    	}
    }

    public function getPatchList() {

    	return Mage::getModel('configurableproductssimpleprices/patches')->getPatches();
    }
}
