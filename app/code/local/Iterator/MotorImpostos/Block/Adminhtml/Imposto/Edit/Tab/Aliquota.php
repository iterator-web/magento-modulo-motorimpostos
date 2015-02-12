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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tab_Aliquota extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $model = Mage::registry('motorimpostos/imposto');
     
        $form = new Varien_Data_Form();
        
        $regimeTributacao = Mage::getStoreConfig('tax/empresa/regimetributacao');
        if($regimeTributacao == 2) {
            $fieldset = $form->addFieldset('base_fieldset', array(
                'legend'    => utf8_encode('Informa��es da Al�quota'),
                'class'     => 'fieldset',
            ));
            
            $fieldset->addField('aliquota_simples', 'hidden', array(
                'name' => 'aliquota_simples',
                'after_element_html' => utf8_encode('Al�quota aplic�vel de c�lculo do cr�dito: <strong>'.$model->getAliquotaSimples().'</strong> (SIMPLES NACIONAL)')
            ));
        } else {
            $fieldset = $form->addFieldset('base_fieldset', array(
                'legend'    => utf8_encode('Informa��es de Al�quotas'),
                'class'     => 'fieldset',
            ));

            $fieldset->addField('aliquota_ir', 'text', array(
                'name'      => 'aliquota_ir',
                'label'     => utf8_encode('Al�quota IR'),
                'title'     => utf8_encode('Al�quota IR'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_csll', 'text', array(
                'name'      => 'aliquota_csll',
                'label'     => utf8_encode('Al�quota CSLL'),
                'title'     => utf8_encode('Al�quota CSLL'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_pis', 'text', array(
                'name'      => 'aliquota_pis',
                'label'     => utf8_encode('Al�quota PIS'),
                'title'     => utf8_encode('Al�quota PIS'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_cofins', 'text', array(
                'name'      => 'aliquota_cofins',
                'label'     => utf8_encode('Al�quota COFINS'),
                'title'     => utf8_encode('Al�quota COFINS'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_ipi', 'text', array(
                'name'      => 'aliquota_ipi',
                'label'     => utf8_encode('Al�quota IPI'),
                'title'     => utf8_encode('Al�quota IPI'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_ii', 'text', array(
                'name'      => 'aliquota_ii',
                'label'     => utf8_encode('Al�quota II'),
                'title'     => utf8_encode('Al�quota II'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_iss', 'text', array(
                'name'      => 'aliquota_iss',
                'label'     => utf8_encode('Al�quota ISS'),
                'title'     => utf8_encode('Al�quota ISS'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));
        }
        
        $fieldsetIbpt = $form->addFieldset('base_fieldset_ibpt', array(
            'legend'    => utf8_encode('Informa��es do Total Aproximado das Al�quotas'),
            'class'     => 'fieldset',
        ));

        $fieldsetIbpt->addField('aliquota_ibpt', 'text', array(
            'name'      => 'aliquota_ibpt',
            'label'     => utf8_encode('Total Aproximado das Al�quotas'),
            'title'     => utf8_encode('Total Aproximado das Al�quotas'),
            'required'  => false,
            'class'     => 'validate-zero-or-greater',
            'after_element_html' => utf8_encode('<p class="note">Total Aproximado das Al�quotas com base na tabela disponibilizada pelo IBPT.</p><p class="note">Este valor deve ser atualizado sempre que uma nova tabela for disponibilizada em: http://deolhonoimposto.ibpt.org.br/</p>')
        ));
        
        $form->setValues($model->getData());
        $this->setForm($form);
    }

}
?>