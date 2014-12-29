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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        
        $this->setDefaultSort('imposto_id');
        $this->setId('iterator_motorimpostos_imposto_grid');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }
     
    protected function _getCollectionClass() {
        return 'motorimpostos/imposto_collection';
    }
     
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->addFieldToFilter('cfop_id', array('eq' => Mage::getSingleton('adminhtml/session')->getCfopId()));
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        $this->addColumn('imposto_id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'center',
                'width' => '50px',
                'index' => 'imposto_id',
                'filter_index' => 'imposto_id'
            )
        );
         
        $this->addColumn('ncm_codigo',
            array(
                'header'=> $this->__('NCM/SH'),
                'width'     => '150px',
                'index' => 'ncm_codigo',
                'filter_index' => 'ncm_codigo'
            )
        );
        
        $this->addColumn('icms_origem',
            array(
                'header' => $this->__('ICMS Origem'),
                'width'     => '300px',
                'index' => 'icms_origem',
                'filter_index' => 'icms_origem',
                'type'      => 'options',
                'options'   => array(
                    0 => 'Nacional',
                    1 => 'Estrangeira',
                    2 => 'Estrangeira - Mercado Interno',
                    3 => 'Nacional - Imp. Sup. 40% e Imp. Inf. 70%',
                    4 => 'Nacional - Conformidade',
                    5 => 'Nacional - Imp. Inf. 40%',
                    6 => 'Estrangeira - Imp. Direta - Sem Similar',
                    7 => 'Estrangeira - Merc. Interno - Sem Similar',
                    8 => 'Nacional - Imp. Sup. 70%'
                ),
            )
        );
        
        $this->addColumn('aliquota_ir',
            array(
                'header'=> $this->__('IR'),
                'width'     => '100px',
                'index' => 'aliquota_ir',
                'filter_index' => 'aliquota_ir'
            )
        );
        
        $this->addColumn('aliquota_csll',
            array(
                'header'=> $this->__('CSLL'),
                'width'     => '100px',
                'index' => 'aliquota_csll',
                'filter_index' => 'aliquota_csll'
            )
        );
        
        $this->addColumn('aliquota_pis',
            array(
                'header'=> $this->__('PIS'),
                'width'     => '100px',
                'index' => 'aliquota_pis',
                'filter_index' => 'aliquota_pis'
            )
        );
        
        $this->addColumn('aliquota_cofins',
            array(
                'header'=> $this->__('COFINS'),
                'width'     => '100px',
                'index' => 'aliquota_cofins',
                'filter_index' => 'aliquota_cofins'
            )
        );
        
        $this->addColumn('aliquota_ipi',
            array(
                'header'=> $this->__('IPI'),
                'width'     => '100px',
                'index' => 'aliquota_ipi',
                'filter_index' => 'aliquota_ipi'
            )
        );
        
        $this->addColumn('aliquota_ii',
            array(
                'header'=> $this->__(utf8_encode('Imp. Importação')),
                'width'     => '100px',
                'index' => 'aliquota_ii',
                'filter_index' => 'aliquota_ii'
            )
        );
        
        $this->addColumn('aliquota_iss',
            array(
                'header'=> $this->__('ISS'),
                'width'     => '100px',
                'index' => 'aliquota_iss',
                'filter_index' => 'aliquota_iss'
            )
        );
        
        $this->addColumn('aliquota_interestadual',
            array(
                'header'=> $this->__('Interestadual'),
                'width'     => '100px',
                'index' => 'aliquota_interestadual',
                'filter_index' => 'aliquota_interestadual'
            )
        );
        
        $this->addColumn('created_time',
            array(
                'header'=> $this->__(utf8_encode('Data Criação')),
                'index' => 'created_time',
                'filter_index' => 'created_time',
                'type' => 'datetime'
            )
        );
        
        $this->addColumn('update_time',
            array(
                'header'=> $this->__(utf8_encode('Data Atualização')),
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
        $this->setMassactionIdField('imposto_id');
        $this->getMassactionBlock()->setFormFieldName('imposto_id');
        $this->getMassactionBlock()->addItem('delete', array(
        'label'=> Mage::helper('motorimpostos')->__('Delete'),
        'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
        'confirm' => Mage::helper('motorimpostos')->__('Are you sure?')
        ));
        return $this;
    }
    
}

?>
