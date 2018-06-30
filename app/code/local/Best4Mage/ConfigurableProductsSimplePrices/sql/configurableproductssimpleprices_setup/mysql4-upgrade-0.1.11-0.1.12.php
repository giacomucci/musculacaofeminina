<?php

$installer = $this;

$installer->startSetup();

$cpspAttr = array('cpsp_enable', 'cpsp_enable_tier', 'cpsp_expand_prices', 'cpsp_show_last_price', 'cpsp_show_lowest', 'cpsp_show_maxregular', 'cpsp_show_stock', 'cpsp_update_fields', 'cpsp_use_preselection', 'cpsp_use_tier_lowest');

foreach ($cpspAttr as $attrId) {
	$installer->updateAttribute('catalog_product', $attrId, 'used_in_product_listing', 1);
}

$installer->endSetup();