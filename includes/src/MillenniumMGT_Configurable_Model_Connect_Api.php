<?php
class MillenniumMGT_Configurable_Model_Connect_Api extends Mage_Api_Model_Resource_Abstract
{
    public function connectlink($IdConfigurable,$variations,$productSimple)
    {
        Mage::app();

        //vamos carregar o produto
        $productConfigurable = Mage::getModel('catalog/product')->setStoreId(0);
        $productConfigurable->load($IdConfigurable);

        //vamos validar algunms condições
        if ($productConfigurable->getId() == '') {
            throw new Exception('Produto ' . $IdConfigurable . ' não encontrado');
        }

        if ($productConfigurable->getTypeId() != Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
            throw new Exception('Produto de sku ' . $productConfigurable->getSku() . ' não é do tipo configurável');
        }

        if (count($variations) > 3) {
            throw new Exception('Não é permitido vincular mais de 3 variações ao produto!');
        }

        if (count($variations) == 0) {
            throw new Exception('É obrigatório o envio de pelo menos uma variação!');
        }

        //vamos carregar as os atributos existentes no produto
        $configurableAttributesData = $productConfigurable->getTypeInstance()->getConfigurableAttributesAsArray();

        if (count($configurableAttributesData) == 0) {
            $attributesIds = array();
            for ($x = 0; $x < count($variations); $x++) {
                $attributesIds[$x] = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $variations[$x]);
                
                //vamos bloquear caso o atributo não seja encontrado
                if ($attributesIds[$x] == '') {
                    throw new Exception('Super Atributo ' . $variations[$x] . ' não encontrado!');
                }
            }

            $productConfigurable->getTypeInstance()->setUsedProductAttributeIds($attributesIds);
        } elseif (count($variations) < count($configurableAttributesData)) {

            throw new Exception('Não é permitido retirar varições do produto');

        } elseif (count($variations) == count($configurableAttributesData)) {

            for ($x1 = 0; $x1 < count($configurableAttributesData); $x1++) {
                $variacaoValida = false;
                for ($x2 = 0; $x2 < count($variations); $x2++) {
                    if (strtoupper($configurableAttributesData[$x1][label]) == strtoupper($variations[$x2])) {
                        $variacaoValida = true;
                        break;
                    }
                }
                if (!$variacaoValida) {
                    throw new Exception('Não é permitido Alterar varições do produto');
                }
            }

        } else {
            for ($x1 = 0; $x1 < count($variations); $x1++) {
                $variacaoValida = false;
                for ($x2 = 0; $x2 < count($configurableAttributesData); $x2++) {
                    if (strtoupper($variations[$x1]) == strtoupper($configurableAttributesData[$x2][label])) {
                        $variacaoValida = true;
                        break;
                    }
                }
                if (!$variacaoValida) {
                    $posicao = count($attributesIds);
                    $attributesIds[$posicao] = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $variations[$x1]);
                }
            }
            $productConfigurable->getTypeInstance()->setUsedProductAttributeIds($attributesIds);
        }
        $configurableAttributesData = $productConfigurable->getTypeInstance()->getConfigurableAttributesAsArray();
        $productConfigurable->setCanSaveConfigurableAttributes(true);
        $productConfigurable->setConfigurableAttributesData($configurableAttributesData);
        $configurableProductsData = array();
        for ($x1 = 0; $x1 < count($productSimple); $x1++) {
            if (count($productSimple[$x1]->attributes) != count($variations)){
                throw new Exception('O numero de atributos enviado para o produto esta divergente das variações!');
            }
            $idSimples = $productSimple[$x1]->idSimples;

            for ($x2 = 0; $x2 < count($productSimple[$x1]->attributes); $x2++) {
                $configurableProductsData[$idSimples] = array($x2 => array(
                    'label' => $productSimple[$x1]->attributes[$x2]->label, 
                    'attribute_id' => $productSimple[$x1]->attributes[$x2]->idAttributes, 
                    'value_index' => $productSimple[$x1]->attributes[$x2]->value, 
                    'is_percent' => '0'
                    //'is_percent' => '1',
                    //'pricing_value' => '100'
                ));

            }
        }
        
        $productConfigurable->setConfigurableProductsData($configurableProductsData);
        $productConfigurable->save();

        return 'T';
        //Mage::log('');
    }
}
