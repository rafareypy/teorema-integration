<?php
class Teorema_Integration_Model_Service_Category extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }


  /**
  *  Função que cria categorias, antes de criar ele busca se a mesma ja não existe com o mesmo nome
  *   caso exista retornara a categoria de mesmo nome
  *   @param $parentid   =  id do pai, se não passar nada vai adicionar o default
  *   @param $name       = nome da categoria
  *   @param $urlKey     = url da categoria
  *   @param $categoryTeorema     = Identificador se a catogoria vem do w.s. teorema sim ou não
  *   @return $category  cateogria Magento
  */
  public function createCategory($parentId = null, $name = null, $urlKey = null, $categoryTeorema ){
    echo "<br/> \n Creating category <br/> \n";

    Mage::app()->setCurrentStore(
                      Mage::getModel('core/store')->load(
                                Mage_Core_Model_App::ADMIN_STORE_ID));

    $category = $this->getCategoryByName($name) ;

    if(!is_null($category) && !is_null($category->getId()) && $category->getId() > 0 )
      return $category ;

    /* supply parent id */
    if(is_null($parentId))
      $parentId = '2';

    $category = new Mage_Catalog_Model_Category();
    $category->setName($name);
    $category->setUrlKey($urlKey);
    $category->setIsActive(1);
    $category->setDisplayMode('PRODUCTS');
    $category->setIsAnchor(0);

    $category['category_teorema'] = $categoryTeorema ;

    $parentCategory = Mage::getModel('catalog/category')->load($parentId);
    $category->setPath($parentCategory->getPath());

    try{
      $category->save();
    }catch(Exception $e){
        $category = null ;
        $message = "Error in save Category <br/>" . $e->getMessage();

        $this->saveErrosLog($message, 'o', 'product', 0, 0);

        echo $message;
    }

    return $category ;

  }


  public function getCategoryByName($name){

    $categoryRestult = null ;

    if(!is_null($name)){
      $categories = Mage::getModel('catalog/category')
      					->getCollection()
      					->addAttributeToSelect('*')
      					//->addIsActiveFilter()
      					//->addLevelFilter(1)
      					->addOrderField('name');

      foreach ($categories as $key => $category)
      {
        if($category->getName() == $name){
          $categoryRestult = $category ;
          break ;
        }
      }

    }

    return $categoryRestult ;

  }



}
