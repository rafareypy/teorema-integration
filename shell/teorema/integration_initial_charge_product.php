<?php
// declarando o magento
ini_set('display_errors', true);
error_reporting(E_ALL);
chdir(dirname(__FILE__));
require '../../app/Mage.php' ;
umask(0);
Mage::app()->getStore();


//integration_initial_charge_product


	echo "\n Este script tem a funcao de criar todos os produtos da tabela teorema_integration_initial   \n";
	echo "\n para o Magento   \n";

	echo "\n Vamos iniciar a carga de produtos..  \n";


	$service = Mage::getModel('teorema_integration/service_product');

	$service->initialCharge();

	echo "\nTerminamos a execucao\n";


?>
