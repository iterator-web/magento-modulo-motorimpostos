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

class Iterator_MotorImpostos_Block_Adminhtml_Cfop_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    
    public function __construct() {  
        parent::__construct();
     
        $this->setId('iterator_motorimpostos_cfop_form');
        $this->setTitle($this->__(utf8_encode('Informações do CFOP')));
    }  
    
    protected function _prepareForm() {  
        $model = Mage::registry('motorimpostos/cfop');
     
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('cfop_id' => $this->getRequest()->getParam('cfop_id'))),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informações do CFOP'),
            'class'     => 'fieldset',
        ));
     
        if ($model->getId()) {
            $fieldset->addField('cfop_id', 'hidden', array(
                'name' => 'cfop_id',
            ));
            
            $fieldset->addField('created_time', 'hidden', array(
                'name' => 'created_time',
            ));
        }
        
        $fieldset->addField('codigo', 'text', array(
            'name'      => 'codigo',
            'label'     => utf8_encode('Código'),
            'title'     => utf8_encode('Código'),
            'maxlength' => '4',
            'required'  => true,
            'class'     => 'validate-number',
        ));
        
        $fieldset->addField('nome', 'text', array(
            'name'      => 'nome',
            'label'     => 'Nome',
            'title'     => 'Nome',
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">Descrição do CFOP.</p>')
        ));
        
        $fieldset->addField('detalhes', 'textarea', array(
            'name'      => 'detalhes',
            'label'     => 'Detalhes',
            'title'     => 'Detalhes',
            'required'  => false,
            'after_element_html' => utf8_encode('<p class="note">Detalhes do CFOP.</p>')
        ));
        
        if (!$model->getId()) {
            $fieldset->addField('cfop_duplicar', 'select', array(
                'label'     => 'Copiar CFOP',
                'title'     => 'Copiar CFOP',
                'name'      => 'cfop_duplicar',
                'values'    => Mage::getResourceModel('motorimpostos/cfop_collection')->toOptionArray(),
                'required'  => false,
                'after_element_html' => utf8_encode('<p class="note">Copiar taxas e impostos do CFOP selecionado.</p>')
            )); 
        }
     
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
     
        return parent::_prepareForm();
    }  
}