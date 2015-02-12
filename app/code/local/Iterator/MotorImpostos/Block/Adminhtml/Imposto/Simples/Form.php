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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Simples_Edit_Form extends Mage_Adminhtml_Block_Widget_Form { 
    
    public function __construct() {  
        parent::__construct();
     
        $this->setId('iterator_motor_impostos_aliquotasn_form');
        $this->setTitle($this->__(utf8_encode('Gerenciar Al�quota - Simples Nacional')));
    }  
    
    protected function _prepareForm() {
        $model = Mage::registry('aliquota_sn');
        
        $data = array();
        if(Mage::getSingleton('adminhtml/session')->getAliquotaSn()){
            $data = Mage::getSingleton('adminhtml/session')->getAliquotaSn();
            Mage::getSingleton('adminhtml/session')->setAliquotaSn(null);
        } elseif ( Mage::registry('aliquota_sn')) {
            $data =  Mage::registry('aliquota_sn');
        }
        $obj = new Varien_Object($data->getData());
     
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/saveAliqSN'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));
        
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informa��es da Al�quota'),
            'class'     => 'fieldset',
        ));
        
        $fieldset->addField('aliquota_simples', 'text', array(
            'name'      => 'aliquota_simples',
            'label'     => utf8_encode('Al�quota c�lculo do cr�dito'),
            'title'     => utf8_encode('Al�quota c�lculo do cr�dito'),
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">Al�quota aplic�vel de c�lculo do cr�dito.</p>')
        ));
     
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
     
        return parent::_prepareForm();
    }  
}
?>