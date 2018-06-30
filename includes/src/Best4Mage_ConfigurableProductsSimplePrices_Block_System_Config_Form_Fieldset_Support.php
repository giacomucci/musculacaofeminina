<?php

class Best4Mage_ConfigurableProductsSimplePrices_Block_System_Config_Form_Fieldset_Support extends Mage_Adminhtml_Block_System_Config_Form_Field {
	
	 public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $id = $element->getHtmlId();
        $html = '<td class="label"><label for="'.$id.'">'.$element->getLabel().'</label></td>';
        $html .= '<td class="value">';
        $html .= $this->__('<a href="http://support.best4mage.com" title="Best4Mage Support">http://support.best4mage.com</a>');
        $html.= '</td>';
        return $this->_decorateRowHtml($element, $html);
    }
}