<?php 

class Best4Mage_ConfigurableProductsSimplePrices_Block_Cart_Item_Configurable extends Mage_Checkout_Block_Cart_Item_Renderer_Configurable
{
	public function getProductName() {
        $name = $this->getItem()->getBuyRequest()->getCustomname(); 
		if($name != '') return $name;
		else return $this->getProduct()->getName();
    }
	
	public function getProductThumbnail() {
        $name = $this->getItem()->getBuyRequest()->getCustomthumb();
		if($name != '') {
			$product = $this->getChildProduct();
        	return $this->helper('catalog/image')->init($product, 'thumbnail');
		}
		return parent::getProductThumbnail();
    }

    
}
?>
