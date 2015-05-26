<?php
class Teorema_Util_Helper_Data extends Mage_Core_Helper_Abstract
{
  public function getLogoSrc()
  {
      if (empty($this->_data['logo_src'])) {
          $this->_data['logo_src'] = Mage::getStoreConfig('design/header/logo_src');
      }
      $src = Mage::getDesign()->getSkinUrl().$this->_data['logo_src'];
      return $src;
  }

  public function getLogoAlt()
  {
      if (empty($this->_data['logo_alt'])) {
          $this->_data['logo_alt'] = Mage::getStoreConfig('design/header/logo_alt');
      }
      return $this->_data['logo_alt'];
  }


}
?>
