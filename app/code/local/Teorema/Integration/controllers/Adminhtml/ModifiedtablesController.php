<?php
class Teorema_Integration_Adminhtml_ModifiedtablesController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('teorema_integration/items')
            ->_addBreadcrumb('Gerenciar Integração Teorema', 'Integração Teorema');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
				//print_r(get_class_methods(get_class($this->getLayout()->getUpdate())));
				//var_dump($this->getLayout()->getUpdate()->asSimplexml());
				//$this->_addContent($this->getLayout()->createBlock('teorema_integration/adminhtml_modifiedtables'));
        $this->renderLayout();
    }

    public function editAction()
    {

        echo "edit Action to Teorema_Integration_Adminhtml_ModifiedtablesController";
        die();

        Mage::getSingleton('adminhtml/session')->addError('Esta tabela não existe');
        $this->_redirect('*/*/');

    }

    public function newAction()
    {
        $this->_forward('edit');
    }

		/*
			Ação para que o usuario possa processar de forma 'manual'
		*/
		public function trysendAction(){

			try{

					$this->executeProcess($this->getRequest()->getParam('id'));

					Mage::getSingleton('adminhtml/session')
										->addSuccess('Tentava enviada, verifique o status.!');

			}catch(Exception $e){
						Mage::getSingleton('adminhtml/session')->addError('Erro ao tentar reenviar registro.!');
						Mage::getSingleton('adminhtml/session')->addError('Erro :' . $e->getMessage());
						Mage::log($e->getMessage(), null, "trysend_error.log");
			}

			$this->_redirect('*/*/');

		}

		/*
			Função que processa dados de tabelas modificadas pelo id
		*/
		public function executeProcess($id){

			if(!is_null($id))
			{
				$collection = Mage::getModel('teorema_integration/tableschanged')->getCollection();
				$collection->addFieldToFilter('id', $id);
				$collection->load();

				$model = $collection->getFirstItem();

				$type = $model->getType() ;

				switch($type){
					case "stock":
						$serviceStock = Mage::getModel('teorema_integration/service_stock');
						$serviceStock->updateStock(array('processing','pending'), $id);
					case "product":
						$serviceProduct = Mage::getModel('teorema_integration/service_product');
						$serviceProduct->updateProductsToTablesChanged(array('processing','pending'), $id);
				}

			}

		}


    public function saveAction()
    {

      echo "saveAction to Teorema_Integration_Adminhtml_ModifiedtablesController";
      die();

      $this->_redirect('*/*/');


    }






    public function deleteAction()
    {
      echo "deleteAction to Teorema_Integration_Adminhtml_ModifiedtablesController";
      die();

        $this->_redirect('*/*/');

    }

    public function gridAction()
    {

        echo "gridAction to Teorema_Integration_Adminhtml_ModifiedtablesController" ;
        die();

        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('teorema_integration/adminhtml_modifiedtables_grid')->toHtml()
        );
    }
}
