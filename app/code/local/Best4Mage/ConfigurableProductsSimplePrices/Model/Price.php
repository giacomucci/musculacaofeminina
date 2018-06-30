<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product type price model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Best4Mage_ConfigurableProductsSimplePrices_Model_Price extends Mage_Catalog_Model_Product_Type_Configurable_Price
{
    /**
     * Get product final price
     *
     * @param   double $qty
     * @param   Mage_Catalog_Model_Product $product
     * @return  double
     */
    public function getFinalPrice($qty=null, $product)
    {
		$module = Mage::app()->getRequest()->getRouteName();
		
		if(!Mage::helper('configurableproductssimpleprices')->isEnable($product)) return parent::getFinalPrice($qty, $product);
		
		$isSingle = true;
		$finalPrice = 0;
		if(Mage::helper('configurableproductssimpleprices')->isTierConfigurable($product)){
			if($product->getTierPriceCount() > 0){
				$tierPrices = array_reverse($product->getTierPrice());
				$allTqty = array();
				foreach($tierPrices as $tier){
					$allTqty[1*$tier['price_qty']] = 1*$tier['price'];
				}
				krsort($allTqty);
				foreach ($allTqty as $tierq => $tierFinalPrice) {
					if($qty >= $tierq){
						$finalPrice = $tierFinalPrice;
						$isSingle = false;
						break;
					}
				}
			}
		}

		if($product->getCustomOption('attributes')){
			
			if($isSingle)	$finalPrice = $this->getFinalConfigurableItemsPrice($product, $qty);
			$finalPrice += $this->_applyOptionsPrice($product, $qty, $finalPrice) - $finalPrice;
			$finalPrice = max(0, $finalPrice);
	
			$product->setFinalPrice($finalPrice);
			return $finalPrice;
		}
		
		return parent::getFinalPrice($qty, $product);
    }

    /**
     * Get Total price for configurable items
     *
     * @param Mage_Catalog_Model_Product $product
     * @param float $finalPrice
     * @return float
     */
    public function getFinalConfigurableItemsPrice($product, $qty = null)
    {
        $price = 0.0;
        $product->getTypeInstance(true)
                ->setStoreFilter($product->getStore(), $product);
				
		if ($product->getCustomOption('attributes')) {
            $selectedAttributes = unserialize($product->getCustomOption('attributes')->getValue());
			if($selectedAttributes) {
				$simpleProduct = $product->getTypeInstance(true)->getProductByAttributes($selectedAttributes, $product);
				if($simpleProduct) {
					$simpleProduct = Mage::getModel('catalog/product')->load($simpleProduct->getId());
					
					// for admin order create/edit
					$custGroupId = null;
					if(Mage::registry('sales_order') && Mage::registry('sales_order')->getId()) {
						$custGroupId = Mage::registry('sales_order')->getCustomerGroupId();
					} else {
						$custGroupId = Mage::getSingleton('adminhtml/sales_order_create')->getQuote()->getCustomerGroupId();
					}
					if($custGroupId) {
						$simpleProduct->setCustomerGroupId($custGroupId);
					}

					$price = $simpleProduct->getFinalPrice($qty, $simpleProduct);
				}
			}
        }

        // add selected custom options price to final price.
		if($product->getCustomOption('additional_options')) {
			$selectedCustomOptions = unserialize($product->getCustomOption('additional_options')->getValue());
			if($selectedCustomOptions) {
				$coPrice = 0;
				foreach($selectedCustomOptions as $co) {
					$coPrice += (int)$co['price'];
				}
				$price += $coPrice;
			}
		}

        /*if($product->hasCpspOptionPrice()){
        	$price += (1*$product->getCpspOptionPrice());
        }*/
        return $price;
    }

}
