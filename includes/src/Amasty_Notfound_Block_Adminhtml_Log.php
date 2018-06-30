<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Notfound
 */   
class Amasty_Notfound_Block_Adminhtml_Log extends Amasty_Notfound_Block_Adminhtml_Abstract
{
    public function __construct()
    {
        $this->_header    = 'Not Found Pages';
        $this->_modelName = 'log';
        parent::__construct();
    }
}