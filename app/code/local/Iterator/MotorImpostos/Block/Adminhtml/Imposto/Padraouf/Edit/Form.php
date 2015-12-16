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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Padraouf_Edit_Form extends Mage_Adminhtml_Block_Widget_Form { 
    
    public function __construct() {  
        parent::__construct();
     
        $this->setId('iterator_motor_impostos_padraouf_form');
        $this->setTitle($this->__(utf8_encode('Gerenciar Impostos por UF')));
    }  
    
    protected function _prepareForm() {
        $model = Mage::registry('impostos_uf');
        
        $data = array();
        if(Mage::getSingleton('adminhtml/session')->getImpostosUf()){
            $data = Mage::getSingleton('adminhtml/session')->getImpostosUf();
            Mage::getSingleton('adminhtml/session')->setImpostosUf(null);
        } elseif ( Mage::registry('impostos_uf')) {
            $data =  Mage::registry('impostos_uf');
        }
        $obj = new Varien_Object($data->getData());
     
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/savePadraoUf'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));
        
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informações de Impostos Por UF'),
            'class'     => 'fieldset',
        ));
        
        $fieldset->addField('imposto_uf', 'text', array(
            'name'      => 'imposto_uf',
            'label'     => 'Impostos por Estados',
            'required'  => true,
        ));

        $impostoUf = $form->getElement('imposto_uf');

        $impostoUf->setRenderer(
            $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_padraouf_edit_renderer_padraouf')
        );
     
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
     
        return parent::_prepareForm();
    }  
}
?>