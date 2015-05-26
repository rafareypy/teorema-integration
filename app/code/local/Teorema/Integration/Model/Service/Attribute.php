<?php
class Teorema_Integration_Model_Service_Attribute extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }


  public function setAttrtibute($attribute, $description)
  {
    if(!$this->attributeValueExists($attribute, $description)){
        $this->createAttributeOption($attribute, $description);
    }
    return $this->attributeValueExists($attribute, $description);
  }

  public function attributeValueExists($attribute, $description){

        $attributeModel = Mage::getModel('eav/entity_attribute');
        $attributeOptionsModel = Mage::getModel('eav/entity_attribute_source_table') ;

        $attributeCode = $attributeModel->getIdByCode('catalog_product', $attribute);


        $attribute = $attributeModel->load($attributeCode);

        $attribute_table = $attributeOptionsModel->setAttribute($attribute);
        $options = $attributeOptionsModel->getAllOptions(false);

        foreach($options as $option){
            if ($option['label'] == $description){
                return $option['value'];
            }
        }
        return false;
    }


    public function createAttributeOption($code, $optionDabel){

        $attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $code);
        $value['option'] = array($optionDabel, $optionDabel);
        $result = array('value' => $value);
        $attribute->setData('option', $result);
        $attribute->save();
        Mage::log("Atributo " . $code . " com label " . $optionDabel . " criado com sucesso", null, 'teorema_manufacturer.log');
    }


}
