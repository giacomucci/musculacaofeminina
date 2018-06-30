<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Notfound
 */
$this->startSetup();

$this->run("
ALTER TABLE `{$this->getTable('amnotfound/log')}` ADD `request_path` varchar(255) NOT NULL AFTER `url`;
ALTER TABLE `{$this->getTable('amnotfound/log')}` ADD `status` TINYINT(1) NOT NULL AFTER `url`;
");

$this->endSetup(); 