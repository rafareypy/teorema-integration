<?php
class Teorema_Integration_Model_Service_Category extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }


  /*
    Função que cria categorias
    $parentid   =  id do pai, se não passar nada vai adicionar o default
    $name       = nome da categoria
    $urlKey     = url da categoria
  */
  public function createCategory($parentId = null, $name = null, $urlKey = null ){
    echo "Creating category";

    Mage::app()->setCurrentStore(
                      Mage::getModel('core/store')->load(
                                Mage_Core_Model_App::ADMIN_STORE_ID));

                                /* supply parent id */
    if(is_null($parentId))
      $parentId = '2';

    $category = new Mage_Catalog_Model_Category();
    $category->setName($name);
    $category->setUrlKey($urlKey);
    $category->setIsActive(1);
    $category->setDisplayMode('PRODUCTS');
    $category->setIsAnchor(0);

    $parentCategory = Mage::getModel('catalog/category')->load($parentId);
    $category->setPath($parentCategory->getPath());

    try{

      $category->save();

      echo "<br/>Save category";

      echo "<br/>id = " . $category->getId();

      //var_dump($categoryTest);

    }catch(Exception $e){
        echo "<br/>Error in save Category ";
        echo $e->getMessage();
    }





    die();
  }



}
