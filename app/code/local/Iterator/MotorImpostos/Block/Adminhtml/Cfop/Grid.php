<?php
 /**
 * Iterator Sistemas Web
 *
 * NOTAS SOBRE LICENÇA
 *
 * Este arquivo de código-fonte está em vigência dentro dos termos da EULA.
 * Ao fazer uso deste arquivo em seu produto, automaticamente você está 
 * concordando com os termos do Contrato de Licença de Usuário Final(EULA)
 * propostos pela empresa Iterator Sistemas Web.
 *
 * =================================================================
 *                   MÓDULO DE MOTOR DE IMPOSTOS
 * =================================================================
 * Este produto foi desenvolvido para o Ecommerce Magento de forma a
 * automatizar cálculos de impostos existentes em operações fiscais.
 * Através deste módulo a loja virtual do contratante do serviço
 * passará a conter diversos cálculos envolvendo documentos fiscais
 * em operações de entradas e também de saídas de forma automática.
 * =================================================================
 *
 * @category   Iterator
 * @package    Iterator_MotorImpostos
 * @author     Ricardo Auler Barrientos <contato@iterator.com.br>
 * @copyright  Copyright (c) Iterator Sistemas Web - CNPJ: 19.717.703/0001-63
 * @license    O Produto é protegido por leis de direitos autorais, bem como outras leis de propriedade intelectual.
 */

class Iterator_MotorImpostos_Block_Adminhtml_Cfop_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        
        $this->setDefaultSort('cfop_id');
        $this->setId('iterator_motorimpostos_cfop_grid');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }
     
    protected function _getCollectionClass() {
        return 'motorimpostos/cfop_collection';
    }
     
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        $this->addColumn('cfop_id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'center',
                'width' => '50px',
                'index' => 'cfop_id',
                'filter_index' => 'cfop_id'
            )
        );
         
        $this->addColumn('codigo',
            array(
                'header'=> $this->__(utf8_encode('Código')),
                'width'     => '150px',
                'index' => 'codigo',
                'filter_index' => 'codigo'
            )
        );
        
        $this->addColumn('nome',
            array(
                'header'=> $this->__('Nome'),
                'index' => 'nome',
                'filter_index' => 'nome'
            )
        );
        
        $this->addColumn('detalhes',
            array(
                'header'=> $this->__('Detalhes'),
                'index' => 'detalhes',
                'filter_index' => 'detalhes'
            )
        );
        
        $this->addColumn('created_time',
            array(
                'header'=> $this->__(utf8_encode('Data Criação')),
                'width'     => '150px',
                'index' => 'created_time',
                'filter_index' => 'created_time',
                'type' => 'datetime'
            )
        );
        
        $this->addColumn('update_time',
            array(
                'header'=> $this->__(utf8_encode('Data Atualização')),
                'width'     => '150px',
                'index' => 'update_time',
                'filter_index' => 'update_time',
                'type' => 'datetime'
            )
        );
        
        $this->addColumn('action',
            array(
                'header'    =>  utf8_encode('Ação'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => 'Editar',
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        
        $this->addExportType('*/*/exportCsv', Mage::helper('controleestoque')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('controleestoque')->__('XML'));
         
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    protected function _prepareMassaction() {
        $this->setMassactionIdField('cfop_id');
        $this->getMassactionBlock()->setFormFieldName('cfop_id');
        $this->getMassactionBlock()->addItem('delete', array(
        'label'=> Mage::helper('motorimpostos')->__('Delete'),
        'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
        'confirm' => Mage::helper('motorimpostos')->__('Are you sure?')
        ));
        return $this;
    }
}

?>
