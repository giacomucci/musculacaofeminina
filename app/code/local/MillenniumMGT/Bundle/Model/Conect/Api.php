<?php
class MillenniumMGT_Bundle_Model_Conect_Api extends Mage_Api_Model_Resource_Abstract
{
    public function conectoptions($bundleOptions, $bundleSelections, $productId, $priceType, $storeid)
    {
        Mage::app();
        
        $bundleOptionsv = array();
        for ($x = 0; $x < count($bundleOptions); $x++) {
             $bundleOptionsv[$x] = array('title' => $bundleOptions[$x]->title , //option title
                                'option_id' => '',
                                'delete' => $bundleOptions[$x]->delete,
                                'type' => $bundleOptions[$x]->type, //option type
                                'required' => $bundleOptions[$x]->required, //is option required
                                'position' => $bundleOptions[$x]->position); //option position
        }

        for ($i = 0; $i < count($bundleSelections); $i++) {
            unset($Selections);
            for ($x = 0; $x < count($bundleSelections[$i]); $x++) {
                $Selections[$x] = array('product_id' => $bundleSelections[$i][$x]->product_id, //if of a product in selection
                                        'delete' => $bundleSelections[$i][$x]->delete,
                                        'selection_price_value' => $bundleSelections[$i][$x]->selection_price_value,
                                        'selection_price_type' => $bundleSelections[$i][$x]->selection_price_type,
                                        'selection_qty' => $bundleSelections[$i][$x]->selection_qty,
                                        'selection_can_change_qty' => $bundleSelections[$i][$x]->selection_can_change_qty,
                                        'position' => $bundleSelections[$i][$x]->position,
                                        'is_default' => 1);
            }
            $bundleSelectionsv[$i] = $Selections;
        }
                        
        $product = Mage::getModel('catalog/product')->setStoreId(0);
        $product->load($productId);

        if (!$product) {
            throw new Exception('Product loaded does not exist');
        }

        Mage::register('product', $product);
        Mage::register('current_product', $product);

        $product->setCanSaveConfigurableAttributes(false);
        $product->setCanSaveCustomOptions(true);
        $product->setBundleOptionsData($bundleOptionsv);
        $product->setBundleSelectionsData($bundleSelectionsv);
        $product->setCanSaveCustomOptions(true);
        $product->setCanSaveBundleSelections(true);
		$product->setPriceType(1);

        $product->save();

        return "T";
    }
}
