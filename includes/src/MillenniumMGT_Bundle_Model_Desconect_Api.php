<?php
class MillenniumMGT_Bundle_Model_Desconect_Api extends Mage_Api_Model_Resource_Abstract
{
    public function desconectoptions($productId)
    {
        Mage::app();
        $product = Mage::getModel('catalog/product')->setStoreId(0);
        $product->load($productId);
        $oldOptions = $product->getTypeInstance(true)->getOptionsCollection($product);
        
        foreach ($oldOptions as $key => $option) {
            $optionModel = Mage::getModel('bundle/option');
            $optionModel->setId($option->option_id);
            $optionModel->delete();
        }

        return "T";
    }
}