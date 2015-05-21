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

    public function newAction() {

      $service = Mage::getModel('teorema_integration/service_product');

      $test =  $service->getProductsToTeorema();

      var_dump($test);
      die();

        $this->_forward('edit');
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
