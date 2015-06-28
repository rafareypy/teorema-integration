<?php
class Teorema_Cart_Block_Adminhtml_Cart_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('teorema_cart_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {        
        $collection =  Mage::getModel('teorema_cart/cart')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);

        $this->addColumn('id', array(
            'header'    => 'ID',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'id',
        ));


        $this->addColumn('customer_id', array(
            'header'    => 'customer_id',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'customer_id',
        ));


        $this->addColumn('email', array(
            'header'    => 'email',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'email',
        ));


        $this->addColumn('status', array(
            'header'    => 'status',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'status',
        ));


        $this->addColumn('grand_total', array(
            'header'    => 'Total',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'grand_total',
            'type'          => 'currency',
            'currency_code' => $currency            
        ));


        $this->addColumn('number_of_retries', array(
            'header'    => 'number_of_retries',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'number_of_retries',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
