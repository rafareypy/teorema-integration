<?php
class Teorema_Integration_Block_Adminhtml_Modifiedtables_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
				$collection =  Mage::getModel('teorema_integration/tableschanged')->getCollection();

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

				$this->addColumn('last_id_updated', array(
            'header'    => 'Id Tabelas Alt. T.',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'last_id_updated',
        ));


				$this->addColumn('status', array(
            'header'    => 'Estado:',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'status',
        ));


				$this->addColumn('code', array(
            'header'    => 'Est. codigo:',
            'align'     =>'left',
            'width'     => '5px',
            'index'     => 'code',
        ));


				$this->addColumn('number_of_retries', array(
            'header'    => 'Tentativas:',
            'align'     =>'left',
            'width'     => '10px',
            'index'     => 'number_of_retries',
        ));


				$this->addColumn('id_value', array(
            'header'    => 'Codigo:',
            'align'     =>'left',
            'width'     => '10px',
            'index'     => 'id_value',
        ));

				$this->addColumn('type', array(
            'header'    => 'Tipo:',
            'align'     =>'left',
            'width'     => '10px',
            'index'     => 'type',
        ));


				$this->addColumn('updated_at', array(
            'header'    => 'Ultima Atulização:',
            'align'     =>'left',
            'width'     => '10px',
            'index'     => 'updated_at',
        ));


        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
