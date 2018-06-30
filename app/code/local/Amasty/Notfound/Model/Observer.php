<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Notfound
 */  
class Amasty_Notfound_Model_Observer
{
    public function processLogin($observer)
    {
        $username = $observer->getEvent()->getUserName();
        $ip  = Mage::app()->getRequest()->getClientIp();
        
        $attempt = Mage::getModel('amnotfound/attempt');
        $attempt
            ->setDate(date('Y-m-d H:i:s'))
    	    ->setUser($username)
    		->setClientIp($ip)
    	    ->save();
        
        return $this;      
    }

	public function process404($observer)
	{
		$id = $observer->getEvent()->getPage()->getIdentifier();

		if ($id == Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_NO_ROUTE_PAGE)) {
			/**
			 * @var $req Mage_Core_Controller_Request_Http
			 */
			$req = $observer->getEvent()->getControllerAction()->getRequest();

			if ($this->inList('ip', $req->getClientIp()))
				return $this;

			if ($this->inList('referrer', $req->getServer('HTTP_REFERER')))
				return $this;

			if ($this->inList('pattern', $req->getRequestUri()))
				return $this;

			if ($this->inList('useragent', $req->getServer('HTTP_USER_AGENT')))
				return $this;

			$url = $req->getPathInfo();
			$url = preg_replace('/^\//', '', $url);
			$storeId = Mage::app()->getStore()->getId();

			//check if log exists
			$resource = Mage::getResourceModel('amnotfound/log');
			if ($resource->errorWasLogged($url, $storeId)) {
				return $this;
			}

			$log = Mage::getModel('amnotfound/log');

			$log->setDate(date('Y-m-d H:i:s'))
				->setStoreId($storeId)
				->setUrl($url)
				->setClientIp($req->getClientIp())
				->setReferer($req->getServer('HTTP_REFERER'))
				->setRequestPath(Mage::helper('amnotfound')->getUrlPath($url))
				->save();
		}

		return $this;
	}

	protected function inList($key, $str)
	{
		$pattrns = Mage::getStoreConfig('amnotfound/log/skip_' . $key);
		if ($pattrns) {
			$pattrns = explode(',', $pattrns);
			foreach ($pattrns as $value) {
				if (false !== strpos($str, trim($value)))
					return true;
			}
		}

		return false;
	}

	public function checkForErrors()
	{
		$freq = $this->_config('freq', true);
		if (!$freq)
			return $this;

		$email = $this->_config('email');
		if (!$email)
			return $this;

		$hasInterests = false;

		foreach ($this->_getTypes() as $type) {
			if ($this->_config('about_' . $type))
				$hasInterests = true;
		}
		if (!$hasInterests)
			return $this;


		$lastRun = $this->_config('last_run', true);
		if (time() - $lastRun < $freq * 3600)
			return $this;

		$txt = '';
		foreach ($this->_getTypes(true) as $type => $descr) {
			if (!$this->_config('about_' . $type))
				continue;
			$numErrors = Mage::getModel('amnotfound/' . $type)->getCountFrom($lastRun);
			if ($numErrors) {
				$txt .= Mage::helper('amnotfound')->__($descr) . ':' . $numErrors . ";\r\n";
			}
		}

		Mage::getModel('core/config_data')
			->load('amnotfound/notification/last_run', 'path')
			->setScope('default')
			->setPath('amnotfound/notification/last_run')
			->setValue(time())
			->save();

		return $this->_sendEmail($txt);
	}
    
    protected function _sendEmail($txt)
    {
        if (!$txt)
            return $this; 
        
        $data = new Varien_Object();
        
        $data->setCreated(date('Y-m-d H:i:s'));
        $data->setErrors($txt);

        $emailTemplate = Mage::getModel('core/email_template')->loadDefault("amnotfound_notification");

        $vars = array(
            "data" => $data
        );

        $emailTemplate->getProcessedTemplate($vars);

        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_' . $this->_config('sender') . '/email'));

        $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_' . $this->_config('sender') . '/name'));

        $emailTemplate->send($this->_config('email'), $this->_config('email'), $vars);
        
        return $this; 
        
    }

	protected function _getTypes($withLabels = false)
	{
		$types = array(
			'attempt' => 'Failed Login Attempts',
			'error'   => 'System Errors',
			'log'     => 'Not Found Pages',
		);
		if (!$withLabels)
			$types = array_keys($types);

		return $types;
	}

	protected function _config($key, $asNumber = false)
	{
		$key = Mage::getStoreConfig('amnotfound/notification/' . $key);
		if ($asNumber)
			$key = intVal($key);

		return $key;
	}
}