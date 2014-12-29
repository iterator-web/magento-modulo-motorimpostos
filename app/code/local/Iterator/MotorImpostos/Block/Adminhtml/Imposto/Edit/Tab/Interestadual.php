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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tab_Interestadual extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $model = Mage::registry('motorimpostos/imposto');
     
        $form = new Varien_Data_Form();
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informações Interestaduais'),
            'class'     => 'fieldset',
        ));
     
        $fieldset->addField('aliquota_interestadual', 'text', array(
            'name'      => 'aliquota_interestadual',
            'label'     => utf8_encode('Alíquota Interestadual'),
            'title'     => utf8_encode('Alíquota Interestadual'),
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