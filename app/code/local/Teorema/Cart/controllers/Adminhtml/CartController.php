<?php
class Teorema_Cart_Adminhtml_CartController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('teorema_cart/items')
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

        echo "edit Action to Teorema_Cart_Adminhtml_CartController";
        die();

        Mage::getSingleton('adminhtml/session')->addError('Esta tabela não existe');
        $this->_redirect('*/*/');

    }

    public function newAction()
    {


        $this->_forward('edit');
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