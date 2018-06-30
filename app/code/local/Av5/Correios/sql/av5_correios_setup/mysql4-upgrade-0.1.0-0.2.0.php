<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$codigo = 'volume_comprimento';
if($setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY,$codigo) === false) {
	$config = array(
			'position' => 1,
			'required'=> 0,
			'label' => 'Comprimento (cm)',
			'type' => 'int',
			'input'=>'text',
			'apply_to'=>'simple,bundle,grouped,configurable',
			'note'=>'Comprimento da embalagem do produto (Para cálculo dos Correios)'
	);
	$setup->addAttribute('catalog_product', $codigo , $config);
}

$codigo = 'volume_altura';
if($setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY,$codigo) === false) {
	$config = array(
			'position' => 1,
			'required'=> 0,
			'label' => 'Altura (cm)',
			'type' => 'int',
			'input'=>'text',
			'apply_to'=>'simple,bundle,grouped,configurable',
			'note'=>'Altura da embalagem do produto (Para cálculo dos Correios)'
	);
	$setup->addAttribute('catalog_product', $codigo , $config);
}

$codigo = 'volume_largura';
if($setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY,$codigo) === false) {
	$config = array(
			'position' => 1,
			'required'=> 0,
			'label' => 'Largura (cm)',
			'type' => 'int',
			'input'=>'text',
			'apply_to'=>'simple,bundle,grouped,configurable',
			'note'=>'Largura da embalagem do produto (Para cálculo dos Correios)'
	);
	$setup->addAttribute('catalog_product', $codigo , $config);
}

$installer->endSetup();