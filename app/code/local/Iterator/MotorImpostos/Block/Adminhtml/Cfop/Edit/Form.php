<?php
 /**
 * Iterator Sistemas Web
 *
 * NOTAS SOBRE LICEN�A
 *
 * Este arquivo de c�digo-fonte est� em vig�ncia dentro dos termos da EULA.
 * Ao fazer uso deste arquivo em seu produto, automaticamente voc� est� 
 * concordando com os termos do Contrato de Licen�a de Usu�rio Final(EULA)
 * propostos pela empresa Iterator Sistemas Web.
 *
 * =================================================================
 *                   M�DULO DE MOTOR DE IMPOSTOS
 * =================================================================
 * Este produto foi desenvolvido para o Ecommerce Magento de forma a
 * automatizar c�lculos de impostos existentes em opera��es fiscais.
 * Atrav�s deste m�dulo a loja virtual do contratante do servi�o
 * passar� a conter diversos c�lculos envolvendo documentos fiscais
 * em opera��es de entradas e tamb�m de sa�das de forma autom�tica.
 * =================================================================
 *
 * @category   Iterator
 * @package    Iterator_MotorImpostos
 * @author     Ricardo Auler Barrientos <contato@iterator.com.br>
 * @copyright  Copyright (c) Iterator Sistemas Web - CNPJ: 19.717.703/0001-63
 * @license    O Produto � protegido por leis de direitos autorais, bem como outras leis de propriedade intelectual.
 */

class Iterator_MotorImpostos_Block_Adminhtml_Cfop_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    
    public function __construct() {  
        parent::__construct();
     
        $this->setId('iterator_motorimpostos_cfop_form');
        $this->setTitle($this->__(utf8_encode('Informa��es do CFOP')));
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
            'legend'    => utf8_encode('Informa��es do CFOP'),
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
            'label'     => utf8_encode('C�digo'),
            'title'     => utf8_encode('C�digo'),
            'maxlength' => '4',
            'required'  => true,
            'class'     => 'validate-number',
        ));
        
        $fieldset->addField('nome', 'text', array(
            'name'      => 'nome',
            'label'     => 'Nome',
            'title'     => 'Nome',
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">Descri��o do CFOP.</p>')
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