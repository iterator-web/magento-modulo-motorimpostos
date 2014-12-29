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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tab_Interestadual extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $model = Mage::registry('motorimpostos/imposto');
     
        $form = new Varien_Data_Form();
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informa��es Interestaduais'),
            'class'     => 'fieldset',
        ));
     
        $fieldset->addField('aliquota_interestadual', 'text', array(
            'name'      => 'aliquota_interestadual',
            'label'     => utf8_encode('Al�quota Interestadual'),
            'title'     => utf8_encode('Al�quota Interestadual'),
            'required'  => false,
            'class'     => 'validate-zero-or-greater',
        ));
        
        $fieldset->addField('imposto_uf', 'text', array(
            'name'      => 'imposto_uf',
            'label'     => 'Impostos por Estados',
            'required'  => true,
        ));

        $impostoUf = $form->getElement('imposto_uf');

        $impostoUf->setRenderer(
            $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_renderer_impostouf')
        );
     
        $form->setValues($model->getData());
        $this->setForm($form);
    }

}
?>