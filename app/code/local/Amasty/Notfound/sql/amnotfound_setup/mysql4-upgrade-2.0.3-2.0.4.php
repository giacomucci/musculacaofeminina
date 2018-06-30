<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Notfound
 */
$this->startSetup();

$this->run("ALTER TABLE `{$this->getTable('amnotfound/log')}` DROP COLUMN `status`;");

$this->endSetup();