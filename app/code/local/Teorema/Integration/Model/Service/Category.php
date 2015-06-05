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
  *   @param $typeTeorema     Tipo do categoria teorema (ex: Familia, grupo , subgrupo, etc) pode ser nulo
  *   @return $category  cateogria Magento
  */
  public function createCategory($parentId = null, $name = null, $urlKey = null, $categoryTeorema, $iDCategoryTeorema , $typeTeorema){
    echo "<br/> \n Creating category <br/> \n";

    Mage::app()->setCurrentStore(
                      Mage::getModel('core/store')->load(
                                Mage_Core_Model_App::ADMIN_STORE_ID));

    $category = null ;

    if($iDCategoryTeorema != null){
      $category = $this->getCategoryByCodeTeorema($iDCategoryTeorema) ;
    }

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
    $category['code_teorema'] = $iDCategoryTeorema ;
    $category['type_teorema'] = $typeTeorema ;



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
      					->addAttributeToSelect('*');

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

  public function getCategoryByCodeTeorema($codeTeorema){

    $categoryResult = null ;

    if(!is_null($codeTeorema)){
      $categories = Mage::getModel('catalog/category')
      					->getCollection()
      					->addAttributeToSelect('*');

      foreach ($categories as $key => $category)
      {
        if($category['code_teorema'] === $codeTeorema){
          return $category ;
        }
      }

    }

    return $categoryResult ;

  }

  /**
  * Função que busca no Magento todas as categorias de um determinado tipo (Teorema)
  * ex: familia, catgoria, subcategoria, etc.
  * @param $typeTeorema tipo da categoria (teorema)
  * @return $category Magento (categoria do magento que tenha o codigo da categoria teorema)
  */
  public function getCategoriesByType($typeTeorema){

    $arrayReturn = array();
    $categories = Mage::getModel('catalog/category')
              ->getCollection()
              ->addAttributeToSelect('*');

     foreach ($categories as $key => $category) {
       if($category['type_teorema'] == $typeTeorema){
         array_push($arrayReturn, $category['entity_id']);
       }
     }

     return $arrayReturn ;

  }


  /**
  * Função que retorna as categorias do produto, com base nas configurções do modulo integração
  * @param $productMagento Produto Magento
  * @param $productJson produto JSON teorema
  * @param $categoryArray categorias default produto
  * @return array com categorias Magento
  */
  public function getCategoriesByConfigurations($productMagento,$productJson, $categoryArray )
  {

    /* TODO verificar no configurações se esta habilitado o afmilia para categoria */
    $categoryArray = $this->getCategoryByFamilia($productMagento,$productJson, $categoryArray);

    return $categoryArray ;


  }

/**
* Função responsavel por Retornar a um determinado produto Magento,
* todas as categorias relacionadas a ele, e que seja do tipo familia (teorema)
* @param $productMagento  produto Magento
* @param $productJson     produot json teorema
* @param $categoryArray    categorias ja com valores
* @return $categoryArray   caetgorias com valores, mais adicional valores familia
*/
public function getCategoryByFamilia($productMagento,$productJson, $categoryArray)
{
  /* TODO verificar para hablitar falimia com categoria do Magetno..*/
  //Verificamos se o produto existe (sinal que devemos atualizar) e eliminamos todas as categorias do tipo familia
  if(isset($productMagento['entity_id'])){
    $categoriesByType = $this->getCategoriesByType('familia');
    foreach ($categoriesByType as $key => $value) {
      $key = array_search($value, $categoryArray);
      if($key){
        unset($categoryArray[$key]);
      }
    }
  }

  #verificando se este produto tem a categoria familia.
  if(isset($productJson->FAMILIA)){
    if(isset($productJson->FAMILIA->FAMILIACODIGO)){
        #buscamos a categoria com o mesmo nome da familia..
        $description  = $productJson->FAMILIA->FAMILIADESCRICAO ;
        $code         = $productJson->FAMILIA->FAMILIACODIGO ;
        $categoryMagento = $this->createCategory(null, $description, $description, true, $code, 'familia');
        array_push($categoryArray, $categoryMagento->getEntityId());
    }
  }
  return $categoryArray ;

}



}
