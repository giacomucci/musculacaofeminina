<?php

/**
 * Out of Stock Subscription index controller
 *
 * @category    BusinessKing
 * @package     BusinessKing_OutofStockSubscription
 */
class BusinessKing_OutofStockSubscription_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{ 
		$productId = $this->getRequest()->getPost('product');
		$email = $this->getRequest()->getPost('subscription_email');
		if ($email && $productId) {
			
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				Mage::getModel('outofstocksubscription/info')->saveSubscrition($productId, $email);
				
				$this->_getSession()->addSuccess($this->__('Subscription added successfully.'));
							
				$product = Mage::getModel('catalog/product')->load($productId);
				//$product->getProductUrl();
				$url = $product->getData('url_path');
				//$this->_redirect('catalog/product/view', array('id'=>$productId));
				$this->_redirect($url);
			}
			else {
				$this->_redirect('');
			}	
		}
		else {
			$this->_redirect('');
		}		
	}
	
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}