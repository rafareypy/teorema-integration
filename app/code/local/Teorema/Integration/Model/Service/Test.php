<?php
class Basic_Teorema_Model_Category{
	private $readConnection;
	private $writeConnection;

	public function __construct(){
		$resource = Mage::getSingleton('core/resource');
		$this->readConnection = $resource->getConnection('core_read');
		$this->writeConnection = $resource->getConnection('core_write');
	}

	public function getMageCategoryIds($level1, $level2 = false, $level3 = false){
		$category_ids = array(1, 3);

		if($level1){
			$mage_category_level1 = $this->getCategory(1, $level1->SUBGRUPOCODIGO);
			if($mage_category_level1){
				array_push($category_ids, $mage_category_level1['mage_id']);
			}else{
				$category_id_level1 = $this->createCategory(1, $level1->SUBGRUPOCODIGO, $level1->SUBGRUPODESCRICAO, $category_ids);
				if($category_id_level1){
					array_push($category_ids, $category_id_level1);
				}
			}
		}

		if($level2){
			$mage_category_level2 = $this->getCategory(2, $level2->GRUPOCODIGO);
			if($mage_category_level2){
				array_push($category_ids, $mage_category_level2['mage_id']);
			}else{
				$category_id_level2 = $this->createCategory(2, $level2->GRUPOCODIGO, $level2->GRUPODESCRICAO, $category_ids);
				if($category_id_level2){
					array_push($category_ids, $category_id_level2);
				}
			}
		}

		if($level3){
			$mage_category_level3 = $this->getCategory(3, $level3->TIPOCODIGO);
			if($mage_category_level3){
				array_push($category_ids, $mage_category_level3['mage_id']);
			}else{
				$category_id_level3 = $this->createCategory(3, $level3->TIPOCODIGO, $level3->TIPODESCRICAO, $category_ids);
				if($category_id_level3){
					array_push($category_ids, $category_id_level3);
				}
			}
		}

		return $category_ids;
	}

	public function getCategory($level, $id){
		$query = 'SELECT * FROM Basic_teorema_category_map WHERE level = ' . $level . ' AND teorema_id = ' . $id;
		$results = $this->readConnection->fetchAll($query);
		if(isset($results[0])){
			return $results[0];
		}
		return false;
	}

	public function createCategory($level, $category_data_id, $category_data_name, $addParentCategories){
		Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
		$category =  Mage::getModel('catalog/category');
		$category->setDisplayMode('PRODUCTS');
		$category->setIsAnchor(1);
		$category->setIsActive(1);
		$category->setIncludeInMenu(1);
		$category->setName(addslashes(utf8_encode(utf8_decode(mb_convert_case($category_data_name, MB_CASE_TITLE, 'UTF-8')))));
		$category->setAttributeSetId(12);
		try {
			$category->save();
			$category_id = $category->getId();
			$categoryPath = "";
			if($addParentCategories){
				foreach ($addParentCategories as $parentCategory) {
					$categoryPath .= $parentCategory . "/";
				}
				//$category->setParentId(end($addParentCategories));
			}

			$category->setLevel($level+1);
			$category->setPath($categoryPath . $category_id);

			$category->save();

			$query = 'INSERT INTO Basic_teorema_category_map (level, mage_id, teorema_id) VALUES (' . $level . ', ' . $category_id . ', ' . $category_data_id  . ')';
			$this->writeConnection->query($query);

			$this->fixChildrenCount();
			return $category_id;
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			return false;
		}
	}

	public function fixChildrenCount(){
		$query = 'UPDATE catalog_category_entity SET children_count = (SELECT COUNT(*) FROM (SELECT * FROM catalog_category_entity) AS table2 WHERE path LIKE CONCAT(catalog_category_entity.path,"/%"));';
		$this->writeConnection->query($query);
	}
}
?>
