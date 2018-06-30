<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Notfound
 */  
class Amasty_Notfound_Model_Mysql4_Attempt extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amnotfound/attempt', 'attempt_id');
    }
    
    public function clear()
    {    
        $this->_getWriteAdapter()->raw_query('TRUNCATE TABLE `' . $this->getMainTable() . '`');
    }

    public function collect($lastRun)
    {    
        return true;
    }
}