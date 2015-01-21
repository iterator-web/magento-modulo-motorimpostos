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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tab_Aliquota extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $model = Mage::registry('motorimpostos/imposto');
     
        $form = new Varien_Data_Form();
        
        $regimeTributacao = Mage::getStoreConfig('tax/empresa/regimetributacao');
        if($regimeTributacao == 2) {
            $fieldset = $form->addFieldset('base_fieldset', array(
                'legend'    => utf8_encode('Informações da Alíquota'),
                'class'     => 'fieldset',
            ));
            
            $fieldset->addField('aliquota_simples', 'hidden', array(
                'name' => 'aliquota_simples',
                'after_element_html' => utf8_encode('Alíquota aplicável de cálculo do crédito: <strong>'.$model->getAliquotaSimples().'</strong> (SIMPLES NACIONAL)')
            ));
        } else {
            $fieldset = $form->addFieldset('base_fieldset', array(
                'legend'    => utf8_encode('Informações de Alíquotas'),
                'class'     => 'fieldset',
            ));

            $fieldset->addField('aliquota_ir', 'text', array(
                'name'      => 'aliquota_ir',
                'label'     => utf8_encode('Alíquota IR'),
                'title'     => utf8_encode('Alíquota IR'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_csll', 'text', array(
                'name'      => 'aliquota_csll',
                'label'     => utf8_encode('Alíquota CSLL'),
                'title'     => utf8_encode('Alíquota CSLL'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_pis', 'text', array(
                'name'      => 'aliquota_pis',
                'label'     => utf8_encode('Alíquota PIS'),
                'title'     => utf8_encode('Alíquota PIS'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_cofins', 'text', array(
                'name'      => 'aliquota_cofins',
                'label'     => utf8_encode('Alíquota COFINS'),
                'title'     => utf8_encode('Alíquota COFINS'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_ipi', 'text', array(
                'name'      => 'aliquota_ipi',
                'label'     => utf8_encode('Alíquota IPI'),
                'title'     => utf8_encode('Alíquota IPI'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_ii', 'text', array(
                'name'      => 'aliquota_ii',
                'label'     => utf8_encode('Alíquota II'),
                'title'     => utf8_encode('Alíquota II'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));

            $fieldset->addField('aliquota_iss', 'text', array(
                'name'      => 'aliquota_iss',
                'label'     => utf8_encode('Alíquota ISS'),
                'title'     => utf8_encode('Alíquota ISS'),
                'required'  => false,
                'class'     => 'validate-zero-or-greater',
            ));
        }
        
        $fieldsetIbpt = $form->addFieldset('base_fieldset_ibpt', array(
            'legend'    => utf8_encode('Informações do Total Aproximado das Alíquotas'),
            'class'     => 'fieldset',
        ));

        $fieldsetIbpt->addField('aliquota_ibpt', 'text', array(
            'name'      => 'aliquota_ibpt',
            'label'     => utf8_encode('Total Aproximado das Alíquotas'),
            'title'     => utf8_encode('Total Aproximado das Alíquotas'),
            'required'  => false,
            'class'     => 'validate-zero-or-greater',
            'after_element_html' => utf8_encode('<p class="note">Total Aproximado das Alíquotas com base na tabela disponibilizada pelo IBPT.</p><p class="note">Este valor deve ser atualizado sempre que uma nova tabela for disponibilizada em: http://deolhonoimposto.ibpt.org.br/</p>')
        ));
        
        $form->setValues($model->getData());
        $this->setForm($form);
    }

}
?>