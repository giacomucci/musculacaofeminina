<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Notfound
 */  
$this->startSetup();

$this->run("
CREATE TABLE `{$this->getTable('amnotfound/log')}` (
  `log_id` mediumint(8) unsigned NOT NULL auto_increment,
  `date` datetime  NOT NULL,
  `url` varchar(255) NOT NULL,
  `referer` text NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$this->endSetup(); 