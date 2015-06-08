<?php
class Teorema_Integration_Model_Category_Types extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{


      /**
       * Option values
       */
      const VALUE_FAMILIA = 'familia';
      const VALUE_GRUPO   = 'grupo';
      const VALUE_SUBGRUPO   = 'subgrupo';
      const VALUE_MARCA   = 'marca';

      /**
       * Retrieve all options array
       * @return array
       */
      public function getAllOptions()
      {
          if (is_null($this->_options)) {
              $this->_options = array(
                array(
                    'label' => 'familia',
                    'value' => self::VALUE_FAMILIA
                ),
                array(
                    'label' => 'grupo',
                    'value' => self::VALUE_GRUPO
                ),

                array(
                    'label' => 'subgrupo',
                    'value' => self::VALUE_SUBGRUPO
                ),

                array(
                    'label' => 'marca',
                    'value' => self::VALUE_MARCA
                ),
              );
          }
          return $this->_options;
      }

      /**
       * Retrieve option array
       *
       * @return array
       */
      public function getOptionArray()
      {
          $_options = array();
          foreach ($this->getAllOptions() as $option) {
              $_options[$option['value']] = $option['label'];
          }
          return $_options;
      }

      /**
       * Get a text for option value
       *
       * @param string|integer $value
       * @return string
       */
      public function getOptionText($value)
      {
          $options = $this->getAllOptions();
          foreach ($options as $option) {
              if ($option['value'] == $value) {
                  return $option['label'];
              }
          }
          return false;
      }

      /**
       * Retrieve flat column definition
       *
       * @return array
       */
      public function getFlatColums()
      {
          $attributeCode = $this->getAttribute()->getAttributeCode();
          $column = array(
              'unsigned'  => false,
              'default'   => null,
              'extra'     => null
          );

          if (Mage::helper('core')->useDbCompatibleMode()) {
              $column['type']     = 'tinyint(1)';
              $column['is_null']  = true;
          } else {
              $column['type']     = Varien_Db_Ddl_Table::TYPE_SMALLINT;
              $column['length']   = 1;
              $column['nullable'] = true;
              $column['comment']  = $attributeCode . ' column';
          }

          return array($attributeCode => $column);
      }

      /**
       * Retrieve Indexes(s) for Flat
       *
       * @return array
       */
      public function getFlatIndexes()
      {
          $indexes = array();

          $index = 'IDX_' . strtoupper($this->getAttribute()->getAttributeCode());
          $indexes[$index] = array(
              'type'      => 'index',
              'fields'    => array($this->getAttribute()->getAttributeCode())
          );

          return $indexes;
      }

      /**
       * Retrieve Select For Flat Attribute update
       *
       * @param int $store
       * @return Varien_Db_Select|null
       */
      public function getFlatUpdateSelect($store)
      {
        return null;
          //return Mage::getResourceModel('eav/entity_attribute')
            //  ->getFlatUpdateSelect($this->getAttribute(), $store);
      }

      /**
       * Get a text for index option value
       *
       * @param  string|int $value
       * @return string|bool
       */
      public function getIndexOptionText($value)
      {
          switch ($value) {
              case self::VALUE_FAMILIA:
                  return 'familia';
              case self::VALUE_GRUPO:
                  return 'grupo';
              case self::VALUE_SUBGRUPO:
                  return 'subgrupo';
              case self::VALUE_MARCA:
                  return 'marca';
          }

          return parent::getIndexOptionText($value);
      }


}
