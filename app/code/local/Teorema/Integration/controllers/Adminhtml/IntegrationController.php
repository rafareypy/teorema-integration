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

      //echo "buscando clientes";

      $service = Mage::getModel('teorema_integration/service_customer');

      //$data = $service->getAllCustomersToTeorema();
      //var_dump($data['data']);
      //die();

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

            if($customer->getId() == 147){
                $result = $service->createCustomerToTeorema($customer) ;


                if(isset($result->CODIGO)){
                  if($result->CODIGO == 0){
                    echo "<br/>Customer " . $result->FIELDS->CLIFORNOME . " saved " ;

                      try{
                        //include code teorema in customer Magento

                      }catch(Exception $e){
                        echo "error in save customer to Magento <br/> " . $e->getMessage();
                      }

                  }else{
                    echo "error in save customer <br/> " . $result->ERRO;
                  }
                }else{
                  echo "<br/>error in Saving customer <br/>" ;
                }


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
    public function newActionTablesChanged(){
      //die("testing update stock");

       //$service_stock = Mage::getModel('teorema_integration/service_stock');

      //$service_stock->updateStock();

      //die("--------------");

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



    public function newAction() {




      //$collection =  Mage::getModel('teorema_integration/errors')->getCollection();
      /*
      $collection = Mage::getModel('teorema_integration/errors')->getCollection();


      foreach ($collection as $key => $value) {
        echo "<br/> valores ";
      }

      die("88888888");

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

      */



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
