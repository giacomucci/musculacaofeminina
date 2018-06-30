<?php
class Av5_Correios_Adminhtml_UpdateController extends Mage_Adminhtml_Controller_Action {
	
	public function indexAction() {
		$this->loadLayout()->renderLayout();
	}
	
	public function postAction() {
		try {
			$service = $this->getRequest()->getParam('service');
			$model = Mage::getModel('av5_correios/updater');
			$result = $model->update($service);
			$helper = Mage::helper("av5_correios");
			$toUpdate = $helper->hasToUpdate($service);
			echo $toUpdate['total'] . "|" . $result['success'] . "|" . $result['errors'];
		} catch (Exception $e) {
			echo "ERRO|" . $e->getMessage();
		}
	}
	
	public function populateAction() {
		try {
			$model = Mage::getModel('av5_correios/updater');
			$model->populate();
			Mage::getSingleton('adminhtml/session')->addSuccess("Banco de dados populado com sucesso!");
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}
	
	public function cleanAction() {
		try {
			$model = Mage::getResourceModel('av5_correios/carrier_correios');
			$model->cleanDatabase();
			Mage::getSingleton('adminhtml/session')->addSuccess("Banco de dados limpo com sucesso!");
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}
}