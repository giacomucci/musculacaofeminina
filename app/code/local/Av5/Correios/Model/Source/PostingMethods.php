<?php
/**
 * AV5 Tecnologia
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL).
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Shipping (Frete)
 * @package    Av5_Correios
 * @copyright  Copyright (c) 2013 Av5 Tecnologia (http://www.av5.com.br)
 * @author     AV5 Tecnologia <anderson@av5.com.br>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Av5_Correios_Model_Source_PostingMethods
{

public function toOptionArray()
    {
        return array(
            array('value'=>'04014', 'label'=>Mage::helper('adminhtml')->__('Sedex Sem Contrato (04014)')),
            array('value'=>'40010', 'label'=>Mage::helper('adminhtml')->__('Sedex Sem Contrato (40010)')),
            array('value'=>'04162', 'label'=>Mage::helper('adminhtml')->__('Sedex Com Contrato (04162)')),
            array('value'=>'40096', 'label'=>Mage::helper('adminhtml')->__('Sedex Com Contrato (40096)')),
        	array('value'=>'40436', 'label'=>Mage::helper('adminhtml')->__('Sedex Com Contrato (40436)')),
        	array('value'=>'40444', 'label'=>Mage::helper('adminhtml')->__('Sedex Com Contrato (40444)')),
            array('value'=>'81019', 'label'=>Mage::helper('adminhtml')->__('E-Sedex Com Contrato (81019)')),
            array('value'=>'04510', 'label'=>Mage::helper('adminhtml')->__('PAC Sem Contrato (04510)')),
            array('value'=>'41106', 'label'=>Mage::helper('adminhtml')->__('PAC Sem Contrato (41106)')),
            array('value'=>'04669', 'label'=>Mage::helper('adminhtml')->__('PAC Com Contrato (04669)')),
            array('value'=>'41068', 'label'=>Mage::helper('adminhtml')->__('PAC Com Contrato (41068)')),
            array('value'=>'40215', 'label'=>Mage::helper('adminhtml')->__('Sedex 10 (40215)')),
            array('value'=>'40290', 'label'=>Mage::helper('adminhtml')->__('Sedex HOJE (40290)')),
            array('value'=>'40045', 'label'=>Mage::helper('adminhtml')->__('Sedex a Cobrar (40045)')),
        );
    }
    
    public function toColumnOptionArray()
    {
    	return array(
    	        '04014' => Mage::helper('adminhtml')->__('Sedex Sem Contrato (04014)'),
    			'40010' => Mage::helper('adminhtml')->__('Sedex Sem Contrato (40010)'),
    	        '04162' => Mage::helper('adminhtml')->__('Sedex Com Contrato (04162)'),
    			'40096' => Mage::helper('adminhtml')->__('Sedex Com Contrato (40096)'),
    			'40436' => Mage::helper('adminhtml')->__('Sedex Com Contrato (40436)'),
    			'40444' => Mage::helper('adminhtml')->__('Sedex Com Contrato (40444)'),
    			'81019' => Mage::helper('adminhtml')->__('E-Sedex Com Contrato (81019)'),
    	        '04510' => Mage::helper('adminhtml')->__('PAC Sem Contrato (04510)'),
    	        '41106' => Mage::helper('adminhtml')->__('PAC Sem Contrato (41106)'),
    			'41068' => Mage::helper('adminhtml')->__('PAC Com Contrato (41068)'),
    	        '04669' => Mage::helper('adminhtml')->__('PAC Com Contrato (04669)'),
    			'40215' => Mage::helper('adminhtml')->__('Sedex 10 (40215)'),
    			'40290' => Mage::helper('adminhtml')->__('Sedex HOJE (40290)'),
    			'40045' => Mage::helper('adminhtml')->__('Sedex a Cobrar (40045)'),
    	);
    } 

}