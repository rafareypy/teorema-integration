<?php
class Teorema_Integration_Block_Adminhtml_Errors_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
				$collection =  Mage::getModel('teorema_integration/errors')->getCollection();

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


				$this->addColumn('id_tables_changed_magento', array(
            'header'    => 'id t.m. Mag.',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'id_tables_changed_magento',
        ));

				$this->addColumn('tables_changed_id_teorema', array(
            'header'    => 'id t.m. Teorema',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'tables_changed_id_teorema',
        ));




				$this->addColumn('message', array(
            'header'    => 'message',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'message',
        ));


				$this->addColumn('code', array(
            'header'    => 'Codigo',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'code',
        ));


				$this->addColumn('type', array(
            'header'    => 'Tipo',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'type',
        ));


				$this->addColumn('updated_at', array(
            'header'    => 'Data',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'updated_at',
        ));
			
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
