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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('imposto_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('motorimpostos')->__(utf8_encode('Informações dos Impostos do NCM')));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('motorimpostos')->__('Geral'),
            'title' => Mage::helper('motorimpostos')->__('Geral'),
            'content' => $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_form')->toHtml(),
        ));

        $this->addTab('aliquota_section', array(
            'label' => Mage::helper('motorimpostos')->__(utf8_encode('Alíquotas')),
            'title' => Mage::helper('motorimpostos')->__(utf8_encode('Alíquotas')),
            'content' => $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_aliquota')->toHtml(),
        ));
        
        $this->addTab('interestadual_section', array(
            'label' => Mage::helper('motorimpostos')->__('Interestaduais'),
            'title' => Mage::helper('motorimpostos')->__('Interestaduais'),
            'content' => $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_interestadual')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
