<?php

class Teorema_Integration_Block_Adminhtml_Integration_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('integartionGrid');
        // This is the primary key of the database
        $this->setDefaultSort('testimonials_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('testimonials_id');
        $this->getMassactionBlock()->setFormFieldName('testimonials_id');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('tax')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
            'confirm' => Mage::helper('tax')->__('Are you sure?')
        ));

        return $this;
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('teorema_integration/integration')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('id', array(
            'header'    => 'Id',
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'id',
        ));

        $this->addColumn('name', array(
            'header'    => 'Nome',
            'align'     =>'left',
            'index'     => 'name',
        ));

        $this->addColumn('email', array(
            'header'    => 'Email',
            'align'     =>'left',
            'index'     => 'email',
        ));

        $this->addColumn('reviewed', array(

            'header'    => 'Revisado',
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'reviewed',
            'type'      => 'options',
            'options'   => array(
                1 => 'Sim',
                0 => 'Não ',
            ),
        ));

        $this->addColumn('created_at', array(
            'header'    => 'Criado em:',
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'created_at',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }


}
