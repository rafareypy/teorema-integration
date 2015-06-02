<?php
// declarando o magento
ini_set('display_errors', true);
error_reporting(E_ALL);
chdir(dirname(__FILE__));
require '../../app/Mage.php' ;
umask(0);
Mage::app()->getStore();



	echo "\n Este script tem a função de trazer todos os valores de sku (ITEMREDUZIDO)   \n";
	echo "\n para o Magento, com status pendente   \n";
	echo "\n logo com outro script podera ser criado os produtos no Magento   \n";

	echo "\n Vamos iniciar a carga de skus   \n";


	$service = Mage::getModel('teorema_integration/service_product');

	$service->chargeSkusTeoremaToInitialModel();

	echo "\nTerminamos a execução\n";


?>
