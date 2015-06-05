<?php

class Teorema_Integration_Adminhtml_IntegrationController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('integration/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction() {
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('teorema_integration/adminhtml_integration'));
        $this->renderLayout();
    }

    public function editAction() {

      $this->renderLayout();

    }





    /*Testes order*/
    public function newActionOrder() {

      //die("Enviando pedidos");

      $orders = Mage::getModel('sales/order')->getCollection();

      $service = Mage::getModel('teorema_integration/service_order');

      foreach ($orders as $key => $order)
      {

        #este pedido foi realizado por um cliente e com produtos importados do webservice teorema
        if($order->getIncrementId() == 145000008){
          echo "<br/> Values to Order : " . $order->getIncrementId();
          $service->sendOrderMagentoToTeorema($order);
        }



      }

      die();
    }


    public function newActionErrors(){

      $errors = Mage::getModel('teorema_integration/errors');



      $errors->setTablesChangedId(1);
      $errors->setCode("1");
      $errors->setCode("1");
      $errors->setType("stock");
      $errors->setMessage("testign");

      try{
          $errors->save();
          echo "<br/> Dados salvos com exito ";
      }catch(Exception $e){
        echo "<br/> Erro ao tentar salvar os dados ";
      }




      die("<br/> Testing table erros ");

    }

    /*
      Testes relacionados a clientes
    */
    public function newActionCustomer() {

      echo "testes relacionados a clientes <br/>";

      $service = Mage::getModel('teorema_integration/service_customer');

      $data = $service->getAllCustomersToTeorema();
      var_dump( json_encode($data['data']) );

      //die("<br/> processo terminado");

      echo "<br/>Creating Customer<br/>";

      #Pendencias:
      #Criar bairro para o cliente
      #Verificar MUNICIPIOCODIGOIBGE
      #Verificar street_number para o endereço


      $collection = Mage::getModel('customer/customer')->getCollection()
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname')
        ->addAttributeToSelect('taxvat')
        ->addAttributeToSelect('email');


        $result = "" ;

        foreach ($collection as $customer)
        {

            if($customer->getId() == 150){

                //die("encontramos o cliente de codigo 148");
                $result = $service->createCustomerMagentoToTeorema($customer) ;

                if(is_null($result->getTeoremaCode()) or $result->getTeoremaCode() == "" ){
                  echo 'erro ao enviar o cliente para o teorema';
                }else{
                  echo "cleinte " . $result->getTeoremaCode() . " criado com sucesso.!";
                }
                die("<br/> Processo terminado ");

            }

        }




 /*
        echo "<br/><br/><br/>";
        var_dump($result->FIELDS->CLIFORCODIGO);

        echo "<br/><br/>";

        var_dump($result->CODIGO);
        echo "<br/><br/><br/><br/><br/>";

        var_dump($result->CLIFORCODIGO);

        echo "<br/><br/><br/><br/>";
*/
        var_dump($result);
        die();

    }

    /*Testes relacionados ao tabelas alteradas*/
    public function newAction(){
      //die("testing update stock");

       //$service_stock = Mage::getModel('teorema_integration/service_stock');

      //$service_stock->updateStock();

      //die("--------------");



      $modelService = Mage::getModel('teorema_integration/service_tableschangedteorema');


      var_dump(json_encode($modelService->getTablesChanged()['data']));

      die("testando tabelas modificadas");


      $tableschangedTeoremaService = Mage::getModel('teorema_integration/service_tableschangedteorema');

      $tableschangedTeoremaService->updateTablesChangedTeorema();


      die();

      $tables_changed = Mage::getModel('teorema_integration/tableschanged');

      try{
        $tables_changed->setLastIdUpdated(1);

        //$tables_changed->save();

      }catch(Exception $e){
        echo "<br/> Erro ao tentar inserir valor em tabelas alteradas ";
      }

      var_dump( get_class_methods($tables_changed) );


    }






    public function newActionCategory() {

      $modelService = Mage::getModel('teorema_integration/service_category');

      $result = $modelService->createCategory(2, 'yes-teorema', 'yes-teorema', true );

      var_dump($result);

      die("processo terminado");

      die("buscando determinado produto no w.s. teorema");

      $service = Mage::getModel('teorema_integration/service_product');

    	//$restult = $service->getAllProductsToTeorema();

      $result = $service->getProductJsonToTeorema('006751');

      $productJson = null ;

      if($result['success']){
        $productJson =  $result['data'] ;
      }




      var_dump(json_encode($productJson->FAMILIA));

      #Criar categoria com  nome da familia..


      die("testes relaionados a produto");


      try{


        $errorsModel = Mage::getModel('teorema_integration/errors');

        $errorsModel->setTablesChangedIdTeorema(1);
        $errorsModel->setCode('1');
        $errorsModel->setType('stock');
        $errorsModel->setMessage("asdfasdf");
        $errorsModel['id_tables_changed_magento'] = 8;


        #verificar as variaveis se não vem como nulo e adicionanas do atualização do estoque
        $errorsModel->save();
        echo " model save";
      }catch(Exception $e){
        echo "Erro ao salvar errors <br/>" . $e->getMessage() ;
        Mage::log("Error in save log Errors ", null, "service_log_errors.log");
      }


      die("77777");

      //$this->testCategories();


      //test ok
      $service = Mage::getModel('teorema_integration/service_product');

      $test = $service->getAllProductsToTeorema() ;

      $result = $service->getAllProductsToTeorema();

      //var_dump(json_encode($result['data'] ));

      $service->initialCharge();

      die("testes para a carga inicial");
      //var_dump( json_encode($service->getProductJsonToTeorema('006747')) );


      //$test =  $service->testError();

      //test ok
      //$test = $service->getProduct('006747');


      //die("Creating products");
      //test
      //$test = $service->updateAllProductsTeoremaToMagento();



      //$test = $service->getAllProductsToTeorema();



      //$service = Mage::getModel('teorema_integration/service_balance');

      //test ok..
      //Verifica o estoque do produto
      //$test = $service->availableBalance('000004');
      //$test = $test['data'];

      //Reserva o estoque do produto
      //$test = $service->reservedBalanceToProduct('000004');
      //$test = $test['data'];


      //$service = Mage::getModel('teorema_integration/service_customer');

      //$test =  $service->getAllCustomersToTeorema("0001");


      var_dump($test);
      die();

        $this->_forward('edit');
    }





    public function testCategories(){

      echo "Testing categories";

      $serviceProduct = Mage::getModel('teorema_integration/service_product');

      $listProducts = $serviceProduct->getAllProductsToTeorema();



      $cont = 8 ;

      foreach ($listProducts['data'] as $key => $p) {

        if($cont == 11){
          echo "<br/>sku = " . $p->ITEMREDUZIDO ;
          //Metodo que ira verificar se o produto existe, se não existir cria o produto..

          $productJson =  $serviceProduct->getProductJsonToTeorema($p->ITEMREDUZIDO); //6745

          $productJson = $productJson['data'];

          echo "<br/> ITEMDESCRICAO2 : " . $productJson->ITEMDESCRICAO2;
          echo "<br/> FAMILIA : " . $productJson->FAMILIA;
          echo "<br/> Grupo : " . $productJson->GRUPO;
          echo "<br/> SUBGRUPO : " . $productJson->SUBGRUPO;

          var_dump($productJson);


        }

        $cont++ ;

      }



      $service = Mage::getModel('teorema_integration/service_category');

      $parentId = 2;
      $name = 'controller1';
      $urlKey = $name . "-url" ;

      $service->createCategory($parentId , $name , $urlKey  );


      die();

    }











    public function saveAction() {

      Mage::getSingleton('adminhtml/session')->addSuccess('Item salvo com sucesso.');
      Mage::getSingleton('adminhtml/session')->setIntegrationData(false);


        $this->_redirect('*/*/');
    }

    public function deleteAction() {

        Mage::getSingleton('adminhtml/session')->addSuccess('Item deletado com sucesso.');

        $this->_redirect('*/*/');

    }

    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('teorema_integration/adminhtml_integration_grid')->toHtml()
        ) ;
    }

    public function massDeleteAction()
    {


        $this->_redirect('*/*/index');
    }
}
