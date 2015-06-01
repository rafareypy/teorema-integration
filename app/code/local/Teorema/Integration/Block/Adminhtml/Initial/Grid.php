<?php
class Teorema_Integration_Block_Adminhtml_Initial_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('teorema_integration_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
				$collection =  Mage::getModel('teorema_integration/initial')->getCollection();

				$this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => 'ID',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'id',
        ));


				$this->addColumn('sku', array(
            'header'    => 'sku',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'sku',
        ));


				$this->addColumn('status', array(
            'header'    => 'status',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'status',
        ));


				$this->addColumn('number_of_retries', array(
            'header'    => 'number_of_retries',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'number_of_retries',
        ));


				$this->addColumn('message', array(
            'header'    => 'message',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'message',
        ));



				$this->addColumn('created_at', array(
            'header'    => 'created_at',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'created_at',
        ));



        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
